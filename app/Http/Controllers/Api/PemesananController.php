<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\Gerbong;
use App\Models\Pemesanan;
use App\Models\Penumpang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DetailPemesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:penumpang,petugas');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $jadwal_id = $request->query('jadwal_id');
        $status = $request->query('status');

        $query = Pemesanan::with(['jadwal.kereta', 'detailPemesanans.penumpang', 'detailPemesanans.gerbong', 'user']);
        
        if ($request->user()->role === 'penumpang') {
            $query->where('user_id', Auth::id());
        }

        $pemesanans = $query->when($search, function ($q) use ($search) {
                return $q->whereHas('detailPemesanans.penumpang', function ($p) use ($search) {
                    $p->where('nama', 'like', "%{$search}%");
                })->orWhereHas('jadwal', function ($q) use ($search) {
                    $q->where('stasiun_awal', 'like', "%{$search}%")
                      ->orWhere('stasiun_akhir', 'like', "%{$search}%");
                });
            })
            ->when($jadwal_id, function ($q) use ($jadwal_id) {
                return $q->where('jadwal_id', $jadwal_id);
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return response()->json($pemesanans, 200);
    }

    public function create()
    {
        $jadwals = Jadwal::with('kereta')->get();
        $gerbongs = Gerbong::all();
        $user = Auth::user();

        $passengers = collect([
            (object) [
                'id' => 'user_' . $user->id,
                'nama' => $user->nama,
                'is_user' => true,
            ]
        ]);

        $userPassengers = Penumpang::where('user_id', $user->id)->get()->map(function ($penumpang) {
            return (object) [
                'id' => $penumpang->id,
                'nama' => $penumpang->nama,
                'is_user' => false,
            ];
        });

        $passengers = $passengers->merge($userPassengers);

        return response()->json([
            'jadwals' => $jadwals,
            'gerbongs' => $gerbongs,
            'passengers' => $passengers,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'gerbong_ids' => 'required|array|min:1',
            'gerbong_ids.*' => 'exists:gerbongs,id',
            'penumpang_ids' => 'required|array|min:1',
            'penumpang_ids.*' => 'required',
            'tanggal' => 'required|date|after_or_equal:today',
        ]);

        if ($request->user()->role === 'penumpang') {
            foreach ($request->penumpang_ids as $penumpang_id) {
                if (strpos($penumpang_id, 'user_') !== 0 && !Penumpang::where('id', $penumpang_id)->where('user_id', Auth::id())->exists()) {
                    return response()->json(['error' => 'Anda hanya dapat memilih penumpang yang Anda buat, Tuan'], 422);
                }
            }
        }

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $user = Auth::user();

        $penumpangIds = array_map(function ($id) use ($user) {
            if (strpos($id, 'user_') === 0) {
                $penumpang = Penumpang::firstOrCreate(
                    ['nik' => $user->nik, 'user_id' => $user->id],
                    ['nama' => $user->nama, 'no_telepon' => $user->no_telepon]
                );
                return $penumpang->id;
            }
            return $id;
        }, $request->penumpang_ids);

        $jumlahPenumpang = count($penumpangIds);
        $totalHarga = $jadwal->harga * $jumlahPenumpang;

        $gerbong_counts = array_count_values($request->gerbong_ids);
        foreach ($gerbong_counts as $gerbong_id => $count) {
            $gerbong = Gerbong::findOrFail($gerbong_id);
            if ($gerbong->jumlah_kursi < $count) {
                return response()->json(['error' => "Kursi di gerbong {$gerbong->kode_gerbong} tidak cukup, Tuan"], 422);
            }
        }

        DB::beginTransaction();
        try {
            $pemesanan = Pemesanan::create([
                'kereta_id' => $request->kereta_id,
                'jadwal_id' => $request->jadwal_id,
                'tanggal' => $request->tanggal,
                'user_id' => Auth::id(),
                'jumlah_penumpang' => $jumlahPenumpang,
                'total_harga' => $totalHarga,
                'status' => 'pending',
                'expired_at' => Carbon::now()->addHours(24),
            ]);

            foreach ($penumpangIds as $i => $penumpangId) {
                $gerbongId = $request->gerbong_ids[$i % count($request->gerbong_ids)];
                $gerbong = Gerbong::findOrFail($gerbongId);

                DetailPemesanan::create([
                    'pemesanan_id' => $pemesanan->id,
                    'penumpang_id' => $penumpangId,
                    'gerbong_id' => $gerbong->id,
                    'kode' => 'TCKT-' . strtoupper(Str::random(8)),
                    'status' => 'booked',
                ]);

                $gerbong->decrement('jumlah_kursi');
            }

            DB::commit();
            return response()->json([
                'message' => 'Pemesanan berhasil dibuat. Silakan lakukan pembayaran sebelum ' . $pemesanan->expired_at->format('d F Y H:i') . ' WIB.',
                'data' => $pemesanan->load(['jadwal.kereta', 'detailPemesanans.penumpang', 'detailPemesanans.gerbong']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat pemesanan: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat pemesanan: ' . $e->getMessage()], 500);
        }
    }

    public function show(Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak, Tuan'], 403);
        }

        $pemesanan->load(['jadwal.kereta', 'detailPemesanans.penumpang.user', 'detailPemesanans.gerbong', 'user']);
        return response()->json($pemesanan, 200);
    }

    public function edit(Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak, Tuan'], 403);
        }

        $jadwals = Jadwal::with('kereta')->get();
        $gerbongs = Gerbong::all();
        $user = Auth::user();

        $passengers = collect([
            (object) [
                'id' => 'user_' . $user->id,
                'nama' => $user->nama,
                'is_user' => true,
            ]
        ]);

        $userPassengers = Penumpang::where('user_id', $user->id)->get()->map(function ($penumpang) {
            return (object) [
                'id' => $penumpang->id,
                'nama' => $penumpang->nama,
                'is_user' => false,
            ];
        });

        $passengers = $passengers->merge($userPassengers);
        $pemesanan->load('detailPemesanans');

        return response()->json([
            'pemesanan' => $pemesanan,
            'jadwals' => $jadwals,
            'gerbongs' => $gerbongs,
            'passengers' => $passengers,
        ], 200);
    }

    public function update(Request $request, Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak, Tuan'], 403);
        }

        if ($pemesanan->status !== 'pending') {
            return response()->json(['error' => 'Hanya pemesanan dengan status pending yang dapat diedit, Tuan'], 422);
        }

        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'gerbong_ids' => 'required|array|min:1',
            'gerbong_ids.*' => 'exists:gerbongs,id',
            'penumpang_ids' => 'required|array|min:1',
            'penumpang_ids.*' => 'required',
            'tanggal' => 'required|date|after_or_equal:today',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        if (Auth::user()->role === 'penumpang') {
            foreach ($request->penumpang_ids as $penumpang_id) {
                if (strpos($penumpang_id, 'user_') !== 0 && !Penumpang::where('id', $penumpang_id)->where('user_id', Auth::id())->exists()) {
                    return response()->json(['error' => 'Anda hanya dapat memilih penumpang yang Anda buat, Tuan'], 422);
                }
            }
        }

        $penumpangIds = array_map(function ($id) use ($request) {
            if (strpos($id, 'user_') === 0) {
                $user = Auth::user();
                $penumpang = Penumpang::firstOrCreate(
                    ['nik' => $user->nik, 'user_id' => $user->id],
                    ['nama' => $user->nama, 'no_telepon' => $user->no_telepon]
                );
                return $penumpang->id;
            }
            return $id;
        }, $request->penumpang_ids);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $jumlahPenumpang = count($penumpangIds);

        $gerbong_counts = array_count_values($request->gerbong_ids);
        foreach ($gerbong_counts as $gerbong_id => $count) {
            $gerbong = Gerbong::findOrFail($gerbong_id);
            $used_seats = $pemesanan->detailPemesanans->where('gerbong_id', $gerbong_id)->count();
            $available_seats = $gerbong->jumlah_kursi + $used_seats;
            if ($available_seats < $count) {
                return response()->json(['error' => "Kursi di gerbong {$gerbong->kode_gerbong} tidak cukup, Tuan"], 422);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($pemesanan->detailPemesanans as $detail) {
                $detail->gerbong->increment('jumlah_kursi');
                $detail->delete();
            }

            $pemesanan->update([
                'jadwal_id' => $request->jadwal_id,
                'tanggal' => $request->tanggal,
                'jumlah_penumpang' => $jumlahPenumpang,
                'total_harga' => $jadwal->harga * $jumlahPenumpang,
                'status' => $request->status,
                'expired_at' => $request->status === 'pending' ? Carbon::now()->addHours(24) : $pemesanan->expired_at,
            ]);

            if ($request->status !== 'paid') {
                foreach ($penumpangIds as $index => $penumpang_id) {
                    $gerbong_id = $request->gerbong_ids[$index % count($request->gerbong_ids)];
                    $gerbong = Gerbong::findOrFail($gerbong_id);

                    DetailPemesanan::create([
                        'pemesanan_id' => $pemesanan->id,
                        'penumpang_id' => $penumpang_id,
                        'gerbong_id' => $gerbong->id,
                        'kode' => 'TCKT-' . strtoupper(Str::random(8)),
                        'status' => 'booked',
                    ]);

                    $gerbong->decrement('jumlah_kursi');
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Pemesanan berhasil diperbarui, Tuan',
                'data' => $pemesanan->load(['jadwal.kereta', 'detailPemesanans.penumpang', 'detailPemesanans.gerbong']),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui pemesanan: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memperbarui pemesanan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak, Tuan'], 403);
        }

        DB::beginTransaction();
        try {
            foreach ($pemesanan->detailPemesanans as $detail) {
                $detail->gerbong->increment('jumlah_kursi');
                $detail->delete();
            }
            $pemesanan->delete();
            DB::commit();
            return response()->json(['message' => 'Pemesanan berhasil dihapus, Tuan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus pemesanan: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus pemesanan: ' . $e->getMessage()], 500);
        }
    }

    public function cancel(Request $request, Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak, Tuan'], 403);
        }
        if ($pemesanan->status !== 'pending') {
            return response()->json(['error' => 'Hanya pemesanan dengan status pending yang dapat dibatalkan, Tuan'], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($pemesanan->detailPemesanans as $detail) {
                $detail->gerbong->increment('jumlah_kursi');
                $detail->update(['status' => 'cancelled']);
            }
            $pemesanan->update(['status' => 'cancelled']);
            DB::commit();
            return response()->json(['message' => 'Pemesanan berhasil dibatalkan, Tuan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membatalkan pemesanan: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membatalkan pemesanan: ' . $e->getMessage()], 500);
        }
    }

    public function checkIn(Request $request, DetailPemesanan $detailPemesanan)
    {
        if ($detailPemesanan->pemesanan->user_id !== Auth::id() && Auth::user()->role !== 'petugas') {
            return response()->json(['error' => 'Akses ditolak, Tuan'], 403);
        }
        if ($detailPemesanan->status !== 'booked') {
            return response()->json(['error' => 'Tiket ini tidak dapat check-in, Tuan'], 422);
        }

        DB::beginTransaction();
        try {
            $detailPemesanan->update(['status' => 'checked_in']);
            DB::commit();
            return response()->json(['message' => 'Tiket berhasil check-in, Tuan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal check-in tiket: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal check-in tiket: ' . $e->getMessage()], 500);
        }
    }

    public function complete(Request $request, DetailPemesanan $detailPemesanan)
    {
        if ($detailPemesanan->pemesanan->user_id !== Auth::id() && Auth::user()->role !== 'petugas') {
            return response()->json(['error' => 'Akses ditolak, Tuan'], 403);
        }
        if ($detailPemesanan->status !== 'checked_in' && $detailPemesanan->status !== 'boarded') {
            return response()->json(['error' => 'Tiket ini tidak dapat ditandai selesai, Tuan'], 422);
        }

        DB::beginTransaction();
        try {
            $detailPemesanan->update(['status' => 'completed']);
            $detailPemesanan->gerbong->increment('jumlah_kursi');
            DB::commit();
            return response()->json(['message' => 'Tiket telah selesai, kursi dikembalikan, Tuan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menandai selesai: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menandai selesai: ' . $e->getMessage()], 500);
        }
    }

    public function getGerbongs($kereta_id)
    {
        $gerbongs = Gerbong::where('kereta_id', $kereta_id)
            ->get(['id', 'kode_gerbong', 'jumlah_kursi']);

        return response()->json($gerbongs, 200);
    }

    public function print(Pemesanan $pemesanan)
    {
        if ($pemesanan->status !== 'paid') {
            return response()->json(['error' => 'Hanya pemesanan yang sudah dibayar yang dapat dicetak, Tuan'], 403);
        }

        if ($pemesanan->user_id !== Auth::id() && Auth::user()->role !== 'petugas') {
            return response()->json(['error' => 'Akses ditolak, Tuan'], 403);
        }

        try {
            $pemesanan->load(['jadwal.kereta', 'detailPemesanans.penumpang.user', 'detailPemesanans.gerbong', 'user']);
            $pdf = Pdf::loadView('user.pemesanan.print', compact('pemesanan'));
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="struk-pemesanan-' . $pemesanan->id . '.pdf"');
        } catch (\Exception $e) {
            Log::error('Gagal menghasilkan PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghasilkan PDF: ' . $e->getMessage()], 500);
        }
    }
}