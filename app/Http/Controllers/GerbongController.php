<?php

namespace App\Http\Controllers;

use App\Models\Gerbong;
use App\Models\Kereta;
use Illuminate\Http\Request;use Illuminate\Routing\Controller;

class GerbongController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $kereta_id = $request->query('kereta_id');
        $keretas = Kereta::all();
        $gerbongs = Gerbong::with('kereta')
            ->when($search, function ($query, $search) {
                return $query->where('kode_gerbong', 'like', "%{$search}%");
            })
            ->when($kereta_id, function ($query, $kereta_id) {
                return $query->where('kereta_id', $kereta_id);
            })
            ->latest()
            ->paginate(10);
        return view('admin.gerbong.index', compact('gerbongs', 'keretas', 'search', 'kereta_id'));
    }

    public function create()
    {
        $keretas = Kereta::all();
        return view('admin.gerbong.create', compact('keretas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'kode_gerbong' => 'required|string|max:255',
            'jumlah_kursi' => 'required|integer|min:1',
        ]);

        Gerbong::create($request->all());

        return redirect()->route('admin.gerbong.index')->with('sukses', 'Gerbong berhasil ditambahkan.');
    }

    public function show(Gerbong $gerbong)
    {
        $gerbong->load('kereta');
        return view('admin.gerbong.show', compact('gerbong'));
    }

    public function edit(Gerbong $gerbong)
    {
        $keretas = Kereta::all();
        return view('admin.gerbong.edit', compact('gerbong', 'keretas'));
    }

    public function update(Request $request, Gerbong $gerbong)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'kode_gerbong' => 'required|string|max:255',
            'jumlah_kursi' => 'required|integer|min:1',
        ]);

        $gerbong->update($request->all());

        return redirect()->route('admin.gerbong.index')->with('sukses', 'Gerbong berhasil diperbarui.');
    }

    public function destroy(Gerbong $gerbong)
    {
        $gerbong->delete();

        return redirect()->route('admin.gerbong.index')->with('sukses', 'Gerbong berhasil dihapus.');
    }
}