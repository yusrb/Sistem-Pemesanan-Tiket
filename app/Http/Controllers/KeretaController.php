<?php

namespace App\Http\Controllers;

use App\Models\Kereta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class KeretaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $keretas = Kereta::with('gerbongs')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
        return view('admin.kereta.index', compact('keretas', 'search'));
    }

    public function create()
    {
        return view('admin.kereta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Kereta::create($request->all());

        return redirect()->route('kereta.index')->with('sukses', 'Kereta berhasil ditambahkan.');
    }

    public function show(Kereta $kereta)
    {
        $kereta->load('gerbongs');
        return view('admin.kereta.show', compact('kereta'));
    }

    public function edit(Kereta $kereta)
    {
        return view('admin.kereta.edit', compact('kereta'));
    }

    public function update(Request $request, Kereta $kereta)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kereta->update($request->all());

        return redirect()->route('kereta.index')->with('sukses', 'Kereta berhasil diperbarui.');
    }

    public function destroy(Kereta $kereta)
    {
        $kereta->delete();

        return redirect()->route('kereta.index')->with('sukses', 'Kereta berhasil dihapus.');
    }
}
