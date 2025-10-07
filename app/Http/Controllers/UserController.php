<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $users = User::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->latest()
            ->paginate(10);
        return view('admin.user.index', compact('users', 'search', 'role'));
    }

    public function create()
    {
        return view('admin.user.create');
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

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.user.index')->with('sukses', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
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

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'nik' => $request->nik,
            'no_telepon' => $request->no_telepon,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('sukses', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with('sukses', 'User berhasil dihapus.');
    }
}