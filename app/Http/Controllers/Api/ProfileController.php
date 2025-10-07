<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:users,nik,' . $user->id,
            'no_telepon' => 'nullable|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->nama = $validated['nama'];
        $user->nik = $validated['nik'];
        $user->no_telepon = $validated['no_telepon'];
        $user->email = $validated['email'];

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('profile_photos', 'public');
            $user->foto = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        return response()->json(['message' => 'Profil berhasil diperbarui, Tuan', 'data' => $user], 200);
    }
}