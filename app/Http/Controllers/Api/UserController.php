<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->query('search'), function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->query('role'), function ($query, $role) {
                return $query->where('role', $role);
            })
            ->latest()
            ->paginate(10);

        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:60|unique:users,email',
            'nik' => 'required|string|max:20|unique:users,nik',
            'no_telepon' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
            'role' => 'required|in:penumpang,petugas,admin',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User berhasil ditambahkan, Tuan', 'data' => $user], 201);
    }

    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:60|unique:users,email,' . $user->id,
            'nik' => 'required|string|max:20|unique:users,nik,' . $user->id,
            'no_telepon' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:penumpang,petugas,admin',
        ]);

        $data = $request->only('nama', 'email', 'nik', 'no_telepon', 'role');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json(['message' => 'User berhasil diperbarui, Tuan', 'data' => $user], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User berhasil dihapus, Tuan'], 200);
    }
}