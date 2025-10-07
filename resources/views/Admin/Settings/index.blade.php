@extends('layouts.app')

@section('judul', 'Setting Website')

@section('konten')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Pengaturan Website</h2>
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label for="nama_website" class="block text-sm font-medium text-gray-700">Nama Website</label>
            <input type="text" name="nama_website" id="nama_website" value="{{ $setting->nama_website }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label for="logo" class="block text-sm font-medium text-gray-700">Logo Website</label>
            <input type="file" name="logo" id="logo" class="mt-1 block w-full">
            @if($setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="mt-2 h-12 w-auto">
            @endif
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
    </form>
</div>
@endsection