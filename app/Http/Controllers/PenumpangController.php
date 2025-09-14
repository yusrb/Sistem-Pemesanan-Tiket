<?php

namespace App\Http\Controllers;

use App\Models\Penumpang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PenumpangController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Penumpang::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
        });

        if (Auth::user()->role === 'penumpang') {
            $query->where('user_id', Auth::id());
        }

        $penumpangs = $query->latest()->paginate(10);
        return view('user.penumpang.index', compact('penumpangs', 'search'));
    }

    public function create()
    {
        return view('user.penumpang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:penumpangs,nik',
            'nama' => 'required|string|max:100',
        ]);

        Penumpang::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('user.penumpang.index')->with('sukses', 'Penumpang berhasil ditambahkan.');
    }

    public function show(Penumpang $penumpang)
    {
        if (Auth::user()->role === 'penumpang' && $penumpang->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        return view('user.penumpang.show', compact('penumpang'));
    }

    public function edit(Penumpang $penumpang)
    {
        if (Auth::user()->role === 'penumpang' && $penumpang->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        return view('user.penumpang.edit', compact('penumpang'));
    }

    public function update(Request $request, Penumpang $penumpang)
    {
        if (Auth::user()->role === 'penumpang' && $penumpang->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $request->validate([
            'nik' => 'required|string|size:16|unique:penumpangs,nik,' . $penumpang->id,
            'nama' => 'required|string|max:100',
        ]);

        $penumpang->update([
            'nik' => $request->nik,
            'nama' => $request->nama,
        ]);

        return redirect()->route('user.penumpang.index')->with('sukses', 'Penumpang berhasil diperbarui.');
    }

    public function destroy(Penumpang $penumpang)
    {
        if (Auth::user()->role === 'penumpang' && $penumpang->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        $penumpang->delete();
        return redirect()->route('user.penumpang.index')->with('sukses', 'Penumpang berhasil dihapus.');
    }
}