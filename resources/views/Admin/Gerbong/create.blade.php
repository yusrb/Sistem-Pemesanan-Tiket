<!-- resources/views/admin/gerbong/create.blade.php -->
@extends('layouts.app')

@section('judul', 'Tambah Gerbong - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Gerbong Baru
                </h1>
                <p class="text-gray-600">Tambahkan gerbong baru ke sistem, <span class="font-semibold">{{ now()->format('d F Y') }}</span></p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="flex items-center space-x-2 text-sm text-gray-600" data-clock>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Terakhir diperbarui: {{ now()->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl p-6 shadow-lg hover:bg-gray-50 transition">
        <form action="{{ route('admin.gerbong.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kereta_id" class="block text-sm font-semibold text-gray-600 mb-2">Kereta</label>
                    <select name="kereta_id" id="kereta_id" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                        <option value="">Pilih Kereta</option>
                        @foreach ($keretas as $kereta)
                            <option value="{{ $kereta->id }}" {{ old('kereta_id') == $kereta->id ? 'selected' : '' }}>{{ $kereta->nama }}</option>
                        @endforeach
                    </select>
                    @error('kereta_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="kode_gerbong" class="block text-sm font-semibold text-gray-600 mb-2">Kode Gerbong</label>
                    <input type="text" name="kode_gerbong" id="kode_gerbong" value="{{ old('kode_gerbong') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    @error('kode_gerbong') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="jumlah_kursi" class="block text-sm font-semibold text-gray-600 mb-2">Jumlah Kursi</label>
                    <input type="number" name="jumlah_kursi" id="jumlah_kursi" value="{{ old('jumlah_kursi', 1) }}" min="1" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    @error('jumlah_kursi') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.gerbong.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition inline-block text-center">Kembali</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition transform hover:scale-105">Simpan Gerbong</button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Update clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' });
            document.querySelector('[data-clock] span').textContent = `Terakhir diperbarui: ${timeString} WIB`;
        }
        updateClock();
        setInterval(updateClock, 60000);

        // Notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-xl shadow-lg z-50 transition-all duration-500 transform translate-x-full max-w-sm border`;
            const colors = {
                success: 'bg-green-50 text-green-700 border-green-200',
                error: 'bg-red-50 text-red-700 border-red-200',
                warning: 'bg-yellow-50 text-yellow-700 border-yellow-200',
                info: 'bg-indigo-50 text-indigo-700 border-indigo-200'
            };
            notification.classList.add(...colors[type].split(' '));
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm">${message}</span>
                </div>
            `;
            document.body.appendChild(notification);
            setTimeout(() => notification.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 500);
            }, 4000);
        }

        // Form submit handler
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', () => {
                showNotification('Menyimpan gerbong baru', 'info');
            });
        }

        // Session notifications
        @if (session('sukses'))
            showNotification('{{ session('sukses') }}', 'success');
        @elseif (session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endsection