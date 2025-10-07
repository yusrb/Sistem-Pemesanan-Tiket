<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Penumpang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;

class PenumpangController extends Controller
{
    public function index(Request $request)
    {
        $penumpangs = Penumpang::where('user_id', Auth::id())
            ->when($request->query('search'), function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return response()->json($penumpangs, 200);
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return response()->json(['error' => 'Sesi telah berakhir. Mohon masuk kembali.'], 401);
        }

        $request->validate([
            'nik' => 'required|string|size:16|unique:penumpangs,nik',
            'nama' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:15',
        ]);

        $penumpang = Penumpang::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'user_id' => $currentUser->id,
        ]);

        return response()->json(['message' => 'Penumpang atas nama ' . $request->nama . ' berhasil ditambahkan.', 'data' => $penumpang], 201);
    }

    public function show(Penumpang $penumpang)
    {
        if ($penumpang->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }
        $penumpang->load('user');
        return response()->json($penumpang, 200);
    }

    public function update(Request $request, Penumpang $penumpang)
    {
        if ($penumpang->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $user = $penumpang->user;

        if (!$user) {
            return response()->json(['error' => 'Data pengguna terkait tidak ditemukan.'], 404);
        }

        $request->validate([
            'nik' => [
                'required',
                'string',
                'size:16',
                Rule::unique('users', 'nik')->ignore($user->id),
                Rule::unique('penumpangs', 'nik')->ignore($penumpang->id),
            ],
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:60',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'no_telepon' => 'nullable|string|max:15',
        ]);
        
        DB::transaction(function () use ($request, $penumpang, $user) {
            $user->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
            ]);
            
            $penumpang->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
            ]);
        });

        return response()->json(['message' => 'Data penumpang berhasil diperbarui.', 'data' => $penumpang], 200);
    }

    public function destroy(Penumpang $penumpang)
    {
        if ($penumpang->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $user = $penumpang->user;

        if (!$user) {
            return response()->json(['error' => 'Gagal menghapus: Data pengguna terkait tidak ditemukan.'], 404);
        }

        DB::transaction(function () use ($penumpang, $user) {
            $user->delete();
            $penumpang->delete();
        });

        return response()->json(['message' => 'Penumpang berhasil dihapus.'], 200);
    }
}