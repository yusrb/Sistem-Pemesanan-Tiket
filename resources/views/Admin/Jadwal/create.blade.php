@extends('layouts.app')

@section('judul', 'Tambah Jadwal - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="bg-white rounded-xl p-6 shadow-lg hover:bg-gray-50 transition">
        <form action="{{ route('admin.jadwal.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dropdown Kereta -->
                <div>
                    <label for="kereta_id" class="block text-sm font-semibold text-gray-600 mb-2">Kereta</label>
                    <select name="kereta_id" id="kereta_id" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                        <option value="">Pilih Kereta</option>
                        @foreach ($keretas as $kereta)
                            <option value="{{ $kereta->id }}">{{ $kereta->nama }}</option>
                        @endforeach
                    </select>
                    @error('kereta_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Stasiun Awal -->
                <div>
                    <label for="stasiun_awal" class="block text-sm font-semibold text-gray-600 mb-2">Stasiun Awal</label>
                    <input type="text" name="stasiun_awal" id="stasiun_awal" value="{{ old('stasiun_awal') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    @error('stasiun_awal') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Stasiun Akhir -->
                <div>
                    <label for="stasiun_akhir" class="block text-sm font-semibold text-gray-600 mb-2">Stasiun Akhir</label>
                    <input type="text" name="stasiun_akhir" id="stasiun_akhir" value="{{ old('stasiun_akhir') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    @error('stasiun_akhir') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jam Berangkat -->
                <div>
                    <label for="jam_berangkat" class="block text-sm font-semibold text-gray-600 mb-2">Jam Berangkat</label>
                    <input type="time" name="jam_berangkat" id="jam_berangkat" value="{{ old('jam_berangkat') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    @error('jam_berangkat') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jam Sampai -->
                <div>
                    <label for="jam_sampai" class="block text-sm font-semibold text-gray-600 mb-2">Jam Sampai</label>
                    <input type="time" name="jam_sampai" id="jam_sampai" value="{{ old('jam_sampai') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    @error('jam_sampai') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label for="harga" class="block text-sm font-semibold text-gray-600 mb-2">Harga</label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500" min="0">
                    @error('harga') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.jadwal.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">Kembali</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition transform hover:scale-105">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</main>
@endsection