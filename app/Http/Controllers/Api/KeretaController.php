<?php
namespace App\Http\Controllers\Api;

use App\Models\Kereta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class KeretaController extends Controller
{
    public function index(Request $request)
    {
        $keretas = Kereta::with('gerbongs')
            ->when($request->query('search'), function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json($keretas, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kereta = Kereta::create($request->all());
        return response()->json(['message' => 'Kereta berhasil ditambahkan, Tuan', 'data' => $kereta], 201);
    }

    public function show(Kereta $kereta)
    {
        $kereta->load('gerbongs');
        return response()->json($kereta, 200);
    }

    public function update(Request $request, Kereta $kereta)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kereta->update($request->all());
        return response()->json(['message' => 'Kereta berhasil diperbarui, Tuan', 'data' => $kereta], 200);
    }

    public function destroy(Kereta $kereta)
    {
        $kereta->delete();
        return response()->json(['message' => 'Kereta berhasil dihapus, Tuan'], 200);
    }
}