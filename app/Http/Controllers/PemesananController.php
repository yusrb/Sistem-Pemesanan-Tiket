<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:penumpang,petugas');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $jadwal_id = $request->query('jadwal_id');
        $status = $request->query('status');

        $query = Pemesanan::with(['jadwal.kereta', 'detailPemesanans.penumpang', 'detailPemesanans.gerbong']);

        $pemesanans = $query->when($search, fn($q) =>
                $q->whereHas('detailPemesanans.penumpang', fn($p) => $p->where('nama', 'like', "%{$search}%"))
            )
            ->when($jadwal_id, fn($q) => $q->where('jadwal_id', $jadwal_id))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        $jadwals = Jadwal::all();

        return view('user.pemesanan.index', compact('pemesanans', 'jadwals', 'search', 'jadwal_id', 'status'));
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

        return view('user.pemesanan.create', compact('jadwals', 'gerbongs', 'passengers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kereta_id'     => 'required|exists:keretas,id',
            'jadwal_id'     => 'required|exists:jadwals,id',
            'gerbong_ids'   => 'required|array',
            'gerbong_ids.*' => 'exists:gerbongs,id',
            'penumpang_ids' => 'required|array',
            'penumpang_ids.*' => 'required',
            'tanggal'       => 'required|date',
        ]);

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

        $pemesanan = Pemesanan::create([
            'kereta_id'        => $request->kereta_id,
            'jadwal_id'        => $request->jadwal_id,
            'tanggal'          => $request->tanggal,
            'user_id'          => Auth::id(),
            'jumlah_penumpang' => $jumlahPenumpang,
            'total_harga'      => $totalHarga,
            'status'           => 'pending',
        ]);

        foreach ($penumpangIds as $i => $penumpangId) {
            $gerbongId = $request->gerbong_ids[$i % count($request->gerbong_ids)];
            $gerbong = Gerbong::findOrFail($gerbongId);

            if ($gerbong->jumlah_kursi <= 0) {
                throw new \Exception("Gerbong {$gerbong->kode_gerbong} sudah penuh.");
            }

            DetailPemesanan::create([
                'pemesanan_id' => $pemesanan->id,
                'penumpang_id' => $penumpangId,
                'gerbong_id'   => $gerbong->id,
                'kode'         => strtoupper(uniqid('TKT')),
                'status'       => 'booked',
            ]);

            $gerbong->decrement('jumlah_kursi', 1);
        }

        return redirect()->route('pemesanan.index')
            ->with('sukses', 'Pemesanan berhasil ditambahkan.');
    }

    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load(['jadwal.kereta', 'detailPemesanans.penumpang.user', 'detailPemesanans.gerbong', 'user']);
        return view('user.pemesanan.show', compact('pemesanan'));
    }

    public function edit(Pemesanan $pemesanan)
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

        $pemesanan->load('detailPemesanans');

        return view('user.pemesanan.edit', compact('pemesanan', 'jadwals', 'gerbongs', 'passengers'));
    }

    public function update(Request $request, Pemesanan $pemesanan)
    {
        $request->validate([
            'jadwal_id'      => 'required|exists:jadwals,id',
            'gerbong_ids'    => 'required|array',
            'gerbong_ids.*'  => 'exists:gerbongs,id',
            'penumpang_ids'  => 'required|array',
            'penumpang_ids.*' => 'required',
            'tanggal'        => 'required|date',
            'status'         => 'required|in:pending,paid,cancelled',
        ]);

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

        DB::transaction(function () use ($request, $pemesanan, $penumpangIds) {
            $jadwal = Jadwal::findOrFail($request->jadwal_id);
            $originalStatus = $pemesanan->status;

            if ($originalStatus !== 'paid' && $request->status === 'paid') {
                foreach ($pemesanan->detailPemesanans as $detail) {
                    $detail->gerbong->increment('jumlah_kursi', 1);
                    $detail->delete();
                }
            } else {
                foreach ($pemesanan->detailPemesanans as $detail) {
                    $detail->gerbong->increment('jumlah_kursi', 1);
                    $detail->delete();
                }

                if ($request->status !== 'paid') {
                    foreach ($penumpangIds as $index => $penumpang_id) {
                        $gerbong_id = $request->gerbong_ids[$index % count($request->gerbong_ids)];
                        $gerbong = Gerbong::findOrFail($gerbong_id);

                        if ($gerbong->jumlah_kursi <= 0) {
                            throw new \Exception("Gerbong {$gerbong->kode_gerbong} sudah penuh.");
                        }

                        DetailPemesanan::create([
                            'pemesanan_id' => $pemesanan->id,
                            'penumpang_id' => $penumpang_id,
                            'gerbong_id'   => $gerbong->id,
                            'kode'         => Str::upper(Str::random(8)),
                            'status'       => 'booked',
                        ]);

                        $gerbong->decrement('jumlah_kursi', 1);
                    }
                }
            }

            $pemesanan->update([
                'jadwal_id'        => $jadwal->id,
                'tanggal'          => $request->tanggal,
                'jumlah_penumpang' => count($penumpangIds),
                'total_harga'      => $jadwal->harga * count($penumpangIds),
                'status'           => $request->status,
            ]);
        });

        return redirect()->route('pemesanan.index')->with('sukses', 'Pemesanan berhasil diperbarui.');
    }

    public function destroy(Pemesanan $pemesanan)
    {
        foreach ($pemesanan->detailPemesanans as $detail) {
            $detail->gerbong->increment('jumlah_kursi', 1);
            $detail->delete();
        }
        $pemesanan->delete();

        return redirect()->route('pemesanan.index')->with('sukses', 'Pemesanan berhasil dihapus.');
    }

    public function getGerbongs($kereta_id)
    {
        $gerbongs = Gerbong::where('kereta_id', $kereta_id)
            ->get(['id', 'kode_gerbong', 'jumlah_kursi']);

        return response()->json($gerbongs);
    }

    public function print(Pemesanan $pemesanan)
    {
        if ($pemesanan->status !== 'paid') {
            abort(403, 'Hanya pemesanan yang sudah dibayar yang dapat dicetak.');
        }

        if ($pemesanan->user_id !== Auth::id() && Auth::user()->role !== 'petugas') {
            abort(403, 'Akses ditolak.');
        }

        $pemesanan->load(['jadwal.kereta', 'detailPemesanans.penumpang.user', 'detailPemesanans.gerbong', 'user']);

        $pdf = Pdf::loadView('user.pemesanan.print', compact('pemesanan'));
        return $pdf->stream('struk-pemesanan-' . $pemesanan->id . '.pdf');
    }
}