<?php

namespace App\Http\Controllers;

use App\Models\Kereta;
use App\Models\Gerbong;
use App\Models\Jadwal;
use App\Models\User;
use App\Models\Pemesanan;
use App\Models\Penumpang;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $keretaCount = Kereta::count();
        $gerbongCount = Gerbong::count();
        $jadwalCount = Jadwal::count();
        $userCount = User::count();
        $pemesananCount = Pemesanan::count();
        $penumpangCount = Penumpang::count();

        $jadwals = Jadwal::with(['kereta', 'pemesanans'])
            ->withCount('pemesanans')
            ->orderBy('pemesanans_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'keretaCount',
            'gerbongCount',
            'jadwalCount',
            'userCount',
            'pemesananCount',
            'penumpangCount',
            'jadwals'
        ));
    }
}