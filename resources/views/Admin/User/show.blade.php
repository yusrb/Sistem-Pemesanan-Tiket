@extends('layouts.app')

@section('judul', 'Detail User - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Detail User
                </h1>
                <p class="text-gray-600">Informasi user {{ $user->nama }}, <span class="font-semibold">{{ now()->format('d F Y') }}</span></p>
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

    <div class="bg-white rounded-xl p-6 shadow-lg hover:bg-gray-50 transition">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-semibold text-gray-600">Nama</p>
                <p class="text-lg text-gray-900">{{ $user->nama }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-600">NIK</p>
                <p class="text-lg text-gray-900">{{ $user->nik }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-600">No Telepon</p>
                <p class="text-lg text-gray-900">{{ $user->no_telepon ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-600">Role</p>
                <p class="text-lg text-gray-900">{{ ucfirst($user->role) }}</p>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.user.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition inline-block text-center">Kembali</a>
            <a href="{{ route('admin.user.edit', $user) }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition transform hover:scale-105">Edit User</a>
            <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user {{ $user->nama }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition transform hover:scale-105">Hapus User</button>
            </form>
        </div>
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

        const deleteForm = document.querySelector('form');
        if (deleteForm) {
            deleteForm.addEventListener('submit', () => {
                showNotification('Menghapus user', 'info');
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