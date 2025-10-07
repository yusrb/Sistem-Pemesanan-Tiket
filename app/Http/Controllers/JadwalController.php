<?php
namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kereta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,petugas');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $kereta_id = $request->query('kereta_id');
        $query = Jadwal::with('kereta');

        $jadwals = $query->when($search, function ($query, $search) {
                return $query->where('stasiun_awal', 'like', "%{$search}%")
                             ->orWhere('stasiun_akhir', 'like', "%{$search}%");
            })
            ->when($kereta_id, function ($query, $kereta_id) {
                return $query->where('kereta_id', $kereta_id);
            })
            ->latest()
            ->paginate(10);

        $keretas = Kereta::all();
        return view('admin.jadwal.index', compact('jadwals', 'keretas', 'search', 'kereta_id'));
    }

    public function create()
    {
        $keretas = Kereta::all();
        return view('admin.jadwal.create', compact('keretas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'stasiun_awal' => 'required|string|max:100',
            'stasiun_akhir' => 'required|string|max:100',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_sampai' => 'required|date_format:H:i',
            'harga' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['jam_berangkat'] = \Carbon\Carbon::createFromFormat('H:i', $request->jam_berangkat, 'Asia/Jakarta')->format('H:i');
        $data['jam_sampai'] = \Carbon\Carbon::createFromFormat('H:i', $request->jam_sampai, 'Asia/Jakarta')->format('H:i');

        Jadwal::create($data);
        return redirect()->route('admin.jadwal.index')->with('sukses', 'Jadwal berhasil ditambahkan.');
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load('kereta');
        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        $keretas = Kereta::all();
        return view('admin.jadwal.edit', compact('jadwal', 'keretas'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kereta_id' => 'required|exists:keretas,id',
            'stasiun_awal' => 'required|string|max:100',
            'stasiun_akhir' => 'required|string|max:100',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_sampai' => 'required|date_format:H:i',
            'harga' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['jam_berangkat'] = \Carbon\Carbon::createFromFormat('H:i', $request->jam_berangkat, 'Asia/Jakarta')->format('H:i');
        $data['jam_sampai'] = \Carbon\Carbon::createFromFormat('H:i', $request->jam_sampai, 'Asia/Jakarta')->format('H:i');

        $jadwal->update($data);
        return redirect()->route('admin.jadwal.index')->with('sukses', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('sukses', 'Jadwal berhasil dihapus.');
    }
}