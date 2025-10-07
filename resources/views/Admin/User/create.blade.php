@extends('layouts.app')

@section('judul', 'Tambah User - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah User Baru
                </h1>
                <p class="text-gray-600">Tambahkan user baru, <span class="font-semibold">{{ now()->format('d F Y') }}</span></p>
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

 <div class="bg-white rounded-lg shadow-lg p-6 hover:bg-gray-50 transition">
            <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-600 mb-2">Nama</label>
                        <div class="relative">
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('nama') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan Nama">
                            <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            </svg>
                        </div>
                        @error('nama') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-600 mb-2">Email</label>
                        <div class="relative">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan Email">
                            <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nik" class="block text-sm font-semibold text-gray-600 mb-2">NIK</label>
                        <div class="relative">
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('nik') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan NIK">
                            <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        @error('nik') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="no_telepon" class="block text-sm font-semibold text-gray-600 mb-2">No Telepon</label>
                        <div class="relative">
                            <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('no_telepon') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan No Telepon">
                            <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m-1 14h7m-7-6h7m-7 6h7"/>
                            </svg>
                        </div>
                        @error('no_telepon') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-600 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" class="w-full pl-10 pr-10 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan Password">
                            <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-600 mb-2">Role</label>
                        <select name="role" id="role" class="w-full border {{ $errors->has('role') ? 'border-red-500' : 'border-blue-300' }} rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="penumpang" {{ old('role') == 'penumpang' ? 'selected' : '' }}>Penumpang</option>
                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('admin.user.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition inline-block text-center">Kembali</a>
                    <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition transform hover:scale-105">Simpan User</button>
                </div>
            </form>
        </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Jakarta' });
            document.querySelector('[data-clock] span').textContent = `Terakhir diperbarui: ${timeString} WIB`;
        }
        updateClock();
        setInterval(updateClock, 60000);

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

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', () => {
                showNotification('Menyimpan user baru', 'info');
            });
        }

        @if (session('sukses'))
            showNotification('{{ session('sukses') }}', 'success');
        @elseif (session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endsection