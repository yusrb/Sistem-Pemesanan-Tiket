<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penumpang;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Tambahkan untuk password

class PenumpangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $search = $request->query('search');

        $penumpangs = Penumpang::where('user_id', Auth::id())
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);
        
        return view('user.penumpang.index', compact('penumpangs', 'search'));
    }

    public function create()
    {
        return view('user.penumpang.create');
    }

    public function store(Request $request)
{
    $currentUser = Auth::user();

    if (!$currentUser) {
        return back()->with('error', 'Sesi telah berakhir. Mohon masuk kembali.');
    }

    $request->validate([
        'nik' => 'required|string|size:16|unique:penumpangs,nik',
        'nama' => 'required|string|max:100',
        'no_telepon' => 'nullable|string|max:15',
    ]);

    Penumpang::create([
        'nik' => $request->nik,
        'nama' => $request->nama,
        'no_telepon' => $request->no_telepon,
        'user_id' => $currentUser->id, // Link to authenticated user
    ]);

    return redirect()->route('penumpang.index')
        ->with('sukses', 'Penumpang atas nama ' . $request->nama . ' berhasil ditambahkan.');
}

    public function show(Penumpang $penumpang)
    {
        // Pastikan relasi 'user' dimuat (eager loading)
        $penumpang->load('user');

        // Otorisasi sudah dilakukan di route model binding atau di method edit, 
        // namun sebaiknya Anda pastikan otorisasi juga di sini (jika diakses langsung)
        if ($penumpang->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('user.penumpang.show', compact('penumpang'));
    }

    public function edit(Penumpang $penumpang)
    {
        // Ensure the penumpang belongs to the authenticated user
        if ($penumpang->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Menggunakan relasi 'user' yang sudah terdefinisi di model Penumpang
        $user = $penumpang->user; 
        
        // Cek data pengguna
        if (!$user) {
            return redirect()->route('penumpang.index')->with('error', 'Data pengguna tidak ditemukan.');
        }

        // KARENA RELASI SUDAH ADA, Anda tidak perlu lagi User::find().
        // $user = User::find($penumpang->user_id); 
        
        return view('user.penumpang.edit', compact('penumpang', 'user'));
    }

    public function update(Request $request, Penumpang $penumpang)
    {
        // PENTING: Untuk update, kita perlu user yang terhubung dengan record Penumpang ini
        $user = $penumpang->user;

        if (!$user) {
            return back()->with('error', 'Data pengguna terkait tidak ditemukan.');
        }

        $request->validate([
            // 1. Validasi NIK: unik di tabel users, kecuali untuk ID user ini
            'nik' => [
                'required',
                'string',
                'size:16',
                Rule::unique('users', 'nik')->ignore($user->id),
                Rule::unique('penumpangs', 'nik')->ignore($penumpang->id),
            ],
            'nama' => 'required|string|max:100',
            // 2. Validasi EMAIL: unik di tabel users, kecuali untuk ID user ini
            'email' => [
                'required',
                'string',
                'email',
                'max:60',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'no_telepon' => 'nullable|string|max:15',
        ]);
        
        // Masukkan semua pembaruan database dalam transaksi
        DB::transaction(function () use ($request, $penumpang, $user) {
            // 1. Update record User yang terkait
            $user->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
            ]);
            
            // 2. Update record Penumpang
            $penumpang->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
            ]);
        });
        
        // PENGHAPUSAN: Baris kode ini tidak lagi relevan
        /* $penumpangRecord = Penumpang::where('nik', $penumpang->nik)
            ->where('user_id', Auth::id())
            ->first();
        // $penumpangRecord tidak lagi diperlukan karena $penumpang sudah mengacu pada record yang benar
        */

        return redirect()->route('penumpang.index')->with('sukses', 'Data penumpang berhasil diperbarui.');
    }

    public function destroy(Penumpang $penumpang)
    {
        // Ensure the penumpang belongs to the authenticated user
        if ($penumpang->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        
        $user = $penumpang->user; // Dapatkan user terkait

        if (!$user) {
            return back()->with('error', 'Gagal menghapus: Data pengguna terkait tidak ditemukan.');
        }

        DB::transaction(function () use ($penumpang, $user) {
            // Hapus User yang terkait
            $user->delete();
            
            // Hapus record Penumpang
            $penumpang->delete();
        });

        return redirect()->route('penumpang.index')->with('sukses', 'Penumpang berhasil dihapus.');
    }
}
