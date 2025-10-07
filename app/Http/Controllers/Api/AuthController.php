<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['message' => 'Login berhasil, Tuan', 'token' => $token, 'user' => $user], 200);
        }

        return response()->json(['error' => 'Email atau kata sandi salah, Tuan'], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:60|unique:users',
            'no_telepon' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password),
            'role' => 'penumpang',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['message' => 'Registrasi berhasil, Tuan', 'token' => $token, 'user' => $user], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Berhasil keluar, Tuan'], 200);
    }
}