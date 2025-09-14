<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PetugasDashboardController extends Controller
{

    public function index()
    {
        $today = now()->format('Y-m-d');
        $pemesanans = Pemesanan::with(['user', 'jadwal.kereta'])
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();
        $total_pemesanan = Pemesanan::whereDate('created_at', $today)->count();
        $total_penumpang = Pemesanan::whereDate('created_at', $today)->sum('jumlah_penumpang');
        $total_harga = Pemesanan::whereDate('created_at', $today)->sum('total_harga');

        return view('petugas.dashboard.index', compact('pemesanans', 'total_pemesanan', 'total_penumpang', 'total_harga'));
    }
}