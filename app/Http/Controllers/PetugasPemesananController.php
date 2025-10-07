<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PetugasPemesananController extends Controller
{
public function index(Request $request)
{
    $search = $request->query('search');
    $status = $request->query('status');

    $query = Pemesanan::with(['user', 'jadwal'])
        ->when($search, function ($q) use ($search) {
            return $q->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhereHas('jadwal', function ($q) use ($search) {
                $q->where('stasiun_awal', 'like', "%{$search}%")
                  ->orWhere('stasiun_akhir', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%")
            ->orWhereHas('detailpemesanans', function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%");
            });
        })
        ->when($status, function ($q) use ($status) {
            return $q->where('status', $status);
        })
        ->latest();

    $pemesanans = $query->paginate(10);

    return view('petugas.pemesanan.index', compact('pemesanans', 'search', 'status'));
}

    public function show(Pemesanan $pemesanan)
    {
        return view('petugas.pemesanan.show', compact('pemesanan'));
    }

    public function validatePemesanan(Request $request, Pemesanan $pemesanan)
    {
        $request->validate([
            'status' => 'required|in:paid,cancelled',
        ]);

        try {
            $pemesanan->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

            if ($request->status === 'paid') {
                $pemesanan->detailPemesanans()->update(['status' => 'booked']);
            }

            return redirect()->route('petugas.pemesanan.index')
                ->with('sukses', 'Pemesanan berhasil divalidasi.');
        } catch (\Exception $e) {
            Log::error('Error validating pemesanan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memvalidasi pemesanan.');
        }
    }

    public function checkIn(Request $request, DetailPemesanan $detailPemesanan)
    {
        try {
            if ($detailPemesanan->pemesanan->status !== 'paid') {
                return redirect()->back()->with('error', 'Pemesanan belum dibayar.');
            }

            $detailPemesanan->update([
                'status' => 'checked_in',
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('sukses', 'Penumpang berhasil check-in.');
        } catch (\Exception $e) {
            Log::error('Error checking in: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal melakukan check-in.');
        }
    }

    public function complete(Request $request, DetailPemesanan $detailPemesanan)
    {
        try {
            if (!in_array($detailPemesanan->status, ['checked_in', 'boarded'])) {
                return redirect()->back()->with('error', 'Penumpang belum check-in.');
            }

            $detailPemesanan->update([
                'status' => 'completed',
                'updated_at' => now(),
            ]);

            if ($detailPemesanan->gerbong) {
                $detailPemesanan->gerbong->increment('jumlah_kursi');
            }

            return redirect()->back()->with('sukses', 'Pemesanan berhasil diselesaikan dan kursi dikembalikan.');
        } catch (\Exception $e) {
            Log::error('Error completing pemesanan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyelesaikan pemesanan.');
        }
    }

}