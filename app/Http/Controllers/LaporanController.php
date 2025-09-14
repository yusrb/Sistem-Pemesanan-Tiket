<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $status = $request->query('status');

        $query = Pemesanan::with(['user', 'jadwal.kereta'])
            ->when($start_date, function ($query, $start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query, $end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            });

        $pemesanans = $query->latest()->paginate(10);
        $total_pemesanan = $query->count();
        $total_harga = $query->sum('total_harga');
        $total_penumpang = $query->sum('jumlah_penumpang');

        return view('admin.laporan.index', compact('pemesanans', 'start_date', 'end_date', 'status', 'total_pemesanan', 'total_harga', 'total_penumpang'));
    }

    public function cetak(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $status = $request->query('status');

        $query = Pemesanan::with(['user', 'jadwal.kereta'])
            ->when($start_date, function ($query, $start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query, $end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            });

        $pemesanans = $query->latest()->get();
        $total_pemesanan = $query->count();
        $total_harga = $query->sum('total_harga');
        $total_penumpang = $query->sum('jumlah_penumpang');

        $pdf = PDF::loadView('admin.laporan.cetak', compact('pemesanans', 'start_date', 'end_date', 'status', 'total_pemesanan', 'total_harga', 'total_penumpang'));
        return $pdf->download('laporan-pemesanan-' . now()->format('YmdHis') . '.pdf');
    }
}