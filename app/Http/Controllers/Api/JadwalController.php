<?php

namespace App\Http\Controllers\Api;

use App\Models\Jadwal;
use App\Models\Kereta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = Jadwal::with('kereta')
            ->when($request->query('search'), function ($query, $search) {
                return $query->where('stasiun_awal', 'like', "%{$search}%")
                            ->orWhere('stasiun_akhir', 'like', "%{$search}%");
            })
            ->when($request->query('kereta_id'), function ($query, $kereta_id) {
                return $query->where('kereta_id', $kereta_id);
            })
            ->latest()
            ->paginate(10);

        return response()->json($jadwals, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'stasiun_awal' => 'required|string|max:100',
            'stasiun_akhir' => 'required|string|max:100',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_sampai' => 'required|date_format:H:i',
            'harga' => 'required|numeric|min:0',
        ]);

        $jadwal = Jadwal::create($request->all());
        return response()->json(['message' => 'Jadwal berhasil ditambahkan, Tuan', 'data' => $jadwal], 201);
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load('kereta');
        return response()->json($jadwal, 200);
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'stasiun_awal' => 'required|string|max:100',
            'stasiun_akhir' => 'required|string|max:100',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_sampai' => 'required|date_format:H:i',
            'harga' => 'required|numeric|min:0',
        ]);

        $jadwal->update($request->all());
        return response()->json(['message' => 'Jadwal berhasil diperbarui, Tuan', 'data' => $jadwal], 200);
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return response()->json(['message' => 'Jadwal berhasil dihapus, Tuan'], 200);
    }
}