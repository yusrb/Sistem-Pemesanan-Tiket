<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

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

        return redirect()->route('profile.edit')->with('sukses', 'Profil berhasil diperbarui.');
    }
}