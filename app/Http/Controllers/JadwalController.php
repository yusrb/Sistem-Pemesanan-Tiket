<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kereta;
use Illuminate\Http\Request;use Illuminate\Routing\Controller;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $kereta_id = $request->query('kereta_id');
        $keretas = Kereta::all();
        $jadwals = Jadwal::with('kereta')
            ->when($search, function ($query, $search) {
                return $query->where('stasiun_awal', 'like', "%{$search}%")
                    ->orWhere('stasiun_akhir', 'like', "%{$search}%");
            })
            ->when($kereta_id, function ($query, $kereta_id) {
                return $query->where('kereta_id', $kereta_id);
            })
            ->latest()
            ->paginate(10);
        return view('admin.jadwal.index', compact('jadwals', 'keretas', 'search', 'kereta_id'));
    }

    public function create()
    {
        $keretas = Kereta::all();
        return view('admin.jadwal.create', compact('keretas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'stasiun_awal' => 'required|string|max:255',
            'stasiun_akhir' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_berangkat' => 'required',
            'jam_sampai' => 'required',
            'harga' => 'required|numeric|min:0',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('jadwal.index')->with('sukses', 'Jadwal berhasil ditambahkan.');
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load('kereta');
        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        $keretas = Kereta::all();
        return view('admin.jadwal.edit', compact('jadwal', 'keretas'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'stasiun_awal' => 'required|string|max:255',
            'stasiun_akhir' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_berangkat' => 'required',
            'jam_sampai' => 'required',
            'harga' => 'required|numeric|min:0',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('sukses', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('sukses', 'Jadwal berhasil dihapus.');
    }
}