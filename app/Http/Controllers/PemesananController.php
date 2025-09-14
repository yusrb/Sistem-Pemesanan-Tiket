<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Gerbong;
use App\Models\Pemesanan;
use App\Models\Penumpang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DetailPemesanan;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $query = Pemesanan::with(['user', 'jadwal']);

        if (Auth::user()->role === 'penumpang') {
            $query->where('user_id', Auth::id());
        }

        $pemesanans = $query->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhereHas('jadwal', function ($q) use ($search) {
                    $q->where('stasiun_awal', 'like', "%{$search}%")
                      ->orWhere('stasiun_akhir', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.pemesanan.index', compact('pemesanans', 'search', 'status'));
    }

    public function create()
    {
        $jadwals = Jadwal::with('kereta')->get();
        $penumpangs = Penumpang::all();
        $gerbongs = Gerbong::all();
        return view('admin.pemesanan.create', compact('jadwals', 'penumpangs', 'gerbongs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'jumlah_penumpang' => 'required|integer|min:1',
            'penumpang_ids' => 'required|array|min:1',
            'penumpang_ids.*' => 'exists:penumpangs,id',
            'gerbong_ids' => 'required|array|min:1',
            'gerbong_ids.*' => 'exists:gerbongs,id',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $total_harga = $jadwal->harga * $request->jumlah_penumpang;

        $pemesanan = Pemesanan::create([
            'user_id' => Auth::id(),
            'jadwal_id' => $request->jadwal_id,
            'jumlah_penumpang' => $request->jumlah_penumpang,
            'total_harga' => $total_harga,
            'status' => 'pending',
        ]);

        foreach ($request->penumpang_ids as $index => $penumpang_id) {
            DetailPemesanan::create([
                'pemesanan_id' => $pemesanan->id,
                'penumpang_id' => $penumpang_id,
                'gerbong_id' => $request->gerbong_ids[$index],
                'kode' => Str::random(10),
                'status' => 'booked',
            ]);
        }

        return redirect()->route('pemesanan.index')->with('sukses', 'Pemesanan berhasil dibuat.');
    }

    public function show(Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        $pemesanan->load(['user', 'jadwal', 'detailPemesanans.penumpang', 'detailPemesanans.gerbong']);
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    public function edit(Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        $jadwals = Jadwal::with('kereta')->get();
        $penumpangs = Penumpang::all();
        $gerbongs = Gerbong::all();
        return view('admin.pemesanan.edit', compact('pemesanan', 'jadwals', 'penumpangs', 'gerbongs'));
    }

    public function update(Request $request, Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'jumlah_penumpang' => 'required|integer|min:1',
            'penumpang_ids' => 'required|array|min:1',
            'penumpang_ids.*' => 'exists:penumpangs,id',
            'gerbong_ids' => 'required|array|min:1',
            'gerbong_ids.*' => 'exists:gerbongs,id',
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $total_harga = $jadwal->harga * $request->jumlah_penumpang;

        $pemesanan->update([
            'jadwal_id' => $request->jadwal_id,
            'jumlah_penumpang' => $request->jumlah_penumpang,
            'total_harga' => $total_harga,
            'status' => $request->status,
        ]);

        $pemesanan->detailPemesanans()->delete();
        foreach ($request->penumpang_ids as $index => $penumpang_id) {
            DetailPemesanan::create([
                'pemesanan_id' => $pemesanan->id,
                'penumpang_id' => $penumpang_id,
                'gerbong_id' => $request->gerbong_ids[$index],
                'kode' => Str::random(10),
                'status' => 'booked',
            ]);
        }

        return redirect()->route('pemesanan.index')->with('sukses', 'Pemesanan berhasil diperbarui.');
    }

    public function destroy(Pemesanan $pemesanan)
    {
        if (Auth::user()->role === 'penumpang' && $pemesanan->user_id !== Auth::id()) {
            abort(403);
        }
        $pemesanan->delete();
        return redirect()->route('pemesanan.index')->with('sukses', 'Pemesanan berhasil dihapus.');
    }
}
