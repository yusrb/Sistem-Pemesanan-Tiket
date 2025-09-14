<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PenumpangDashboardController extends Controller
{

    public function index()
    {
        $pemesanans = Pemesanan::with(['jadwal.kereta'])
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $total_pemesanan = Pemesanan::where('user_id', Auth::id())->count();
        $total_penumpang = Pemesanan::where('user_id', Auth::id())->sum('jumlah_penumpang');
        $total_harga = Pemesanan::where('user_id', Auth::id())->sum('total_harga');

        return view('user.dashboard.index', compact(
            'pemesanans',
            'total_pemesanan',
            'total_penumpang',
            'total_harga'
        ));
    }
}
