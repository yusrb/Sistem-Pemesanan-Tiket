@extends('layouts.app')

@section('judul', 'Daftar User - KAI')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">

        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Daftar User
                </h1>
                <p class="text-gray-600">Kelola data pengguna, <span class="font-semibold">{{ now()->format('d F Y') }}</span></p>
            </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-2 text-sm text-gray-600" data-clock>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Terakhir diperbarui: {{ now()->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-6 hover:bg-gray-50 transition">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <form method="GET" action="{{ route('admin.user.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau NIK..." class="w-full pl-10 pr-3 py-2 border {{ $errors->has('search') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <select name="role" class="border {{ $errors->has('role') ? 'border-red-500' : 'border-blue-300' }} rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Role</option>
                            <option value="penumpang" {{ $role == 'penumpang' ? 'selected' : '' }}>Penumpang</option>
                            <option value="petugas" {{ $role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition transform hover:scale-105">Cari</button>
                </form>
                <a href="{{ route('admin.user.create') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 hover:bg-blue-800 transition transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Tambah User</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-blue-50 transition cursor-pointer" onclick="window.location='{{ route('admin.user.show', $user) }}'">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->nik }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->no_telepon ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($user->role) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.user.edit', $user) }}" onclick="event.stopPropagation()" class="text-blue-600 hover:text-blue-800 hover:underline">Edit</a>
                                        <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline" onsubmit="event.stopPropagation(); return confirm('Hapus user {{ $user->nama }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="event.stopPropagation()" class="text-red-600 hover:text-red-800 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-600">Tidak ada user ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-200">
                {{ $users->appends(['search' => $search, 'role' => $role])->links('pagination::tailwind') }}
            </div>
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
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-500 transform translate-x-full max-w-sm border`;
            const colors = {
                success: 'bg-green-50 text-green-700 border-green-200',
                error: 'bg-red-50 text-red-700 border-red-200',
                info: 'bg-blue-50 text-blue-700 border-blue-200'
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

        document.querySelectorAll('table tr.cursor-pointer').forEach(row => {
            row.addEventListener('click', (e) => {
                if (!e.target.closest('a') && !e.target.closest('button')) {
                    showNotification('Menuju detail user', 'info');
                }
            });
        });

        const searchForm = document.querySelector('form');
        if (searchForm) {
            searchForm.addEventListener('submit', () => {
                const searchValue = document.querySelector('input[name="search"]').value;
                const roleValue = document.querySelector('select[name="role"]').value;
                if (searchValue || roleValue) {
                    showNotification(`Mencari: ${searchValue || 'Semua'} di role ${roleValue || 'Semua'}`, 'info');
                }
            });
        }

        const tambahButton = document.querySelector('a[href="{{ route('admin.user.create') }}"]');
        if (tambahButton) {
            tambahButton.addEventListener('click', () => {
                showNotification('Menuju tambah user', 'info');
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