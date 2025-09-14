<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $jadwalCount = Jadwal::count();
        $pemesananAktif = Pemesanan::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'paid'])
            ->count();
        $totalBelanja = Pemesanan::where('user_id', Auth::id())
            ->sum('total_harga');

        $jadwals = Jadwal::with('kereta')->take(5)->get();
        $pemesanans = Pemesanan::with(['jadwal.kereta', 'detailPemesanans'])
            ->where('user_id', Auth::id())
            ->take(3)
            ->get();

        return view('dashboard.penumpang', compact(
            'jadwalCount',
            'pemesananAktif',
            'totalBelanja',
            'jadwals',
            'pemesanans'
        ));
    }
}