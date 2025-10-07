<?php

namespace App\Http\Controllers\Api;

use App\Models\Gerbong;
use App\Models\Kereta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GerbongController extends Controller
{
    public function index(Request $request)
    {
        $gerbongs = Gerbong::with('kereta')
            ->when($request->query('search'), function ($query, $search) {
                return $query->where('kode_gerbong', 'like', "%{$search}%");
            })
            ->when($request->query('kereta_id'), function ($query, $kereta_id) {
                return $query->where('kereta_id', $kereta_id);
            })
            ->latest()
            ->paginate(10);

        return response()->json($gerbongs, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'kode_gerbong' => 'required|string|max:255',
            'jumlah_kursi' => 'required|integer|min:1',
        ]);

        $gerbong = Gerbong::create($request->all());
        return response()->json(['message' => 'Gerbong berhasil ditambahkan, Tuan', 'data' => $gerbong], 201);
    }

    public function show(Gerbong $gerbong)
    {
        $gerbong->load('kereta');
        return response()->json($gerbong, 200);
    }

    public function update(Request $request, Gerbong $gerbong)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'kode_gerbong' => 'required|string|max:255',
            'jumlah_kursi' => 'required|integer|min:1',
        ]);

        $gerbong->update($request->all());
        return response()->json(['message' => 'Gerbong berhasil diperbarui, Tuan', 'data' => $gerbong], 200);
    }

    public function destroy(Gerbong $gerbong)
    {
        $gerbong->delete();
        return response()->json(['message' => 'Gerbong berhasil dihapus, Tuan'], 200);
    }
}