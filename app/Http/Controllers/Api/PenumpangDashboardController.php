<?php

namespace App\Http\Controllers\Api;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class PenumpangDashboardController extends Controller
{
    public function index(Request $request)
    {
        $pemesanans = Pemesanan::with(['jadwal.kereta'])
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $totals = [
            'total_pemesanan' => Pemesanan::where('user_id', Auth::id())->count(),
            'total_penumpang' => Pemesanan::where('user_id', Auth::id())->sum('jumlah_penumpang'),
            'total_harga' => Pemesanan::where('user_id', Auth::id())->sum('total_harga'),
        ];

        return response()->json(['pemesanans' => $pemesanans, 'totals' => $totals], 200);
    }
}