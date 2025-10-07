@extends('layouts.app')

@section('judul', 'Daftar Jadwal - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Daftar Jadwal
                </h1>
                <p class="text-gray-600">Kelola jadwal kereta, <span class="font-semibold">{{ now()->format('d F Y') }}</span></p>
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

    <div class="bg-white rounded-xl p-6 shadow-lg mb-6 hover:bg-gray-50 transition">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari stasiun..." class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <select name="kereta_id" class="border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Kereta</option>
                        @foreach ($keretas as $kereta)
                            <option value="{{ $kereta->id }}" {{ $kereta_id == $kereta->id ? 'selected' : '' }}>{{ $kereta->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition transform hover:scale-105">Cari</button>
            </form>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.jadwal.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 hover:bg-indigo-700 transition transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Tambah Jadwal</span>
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kereta</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stasiun Awal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stasiun Akhir</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jam Berangkat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jam Sampai</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($jadwals as $jadwal)
                        <tr class="hover:bg-indigo-50 transition cursor-pointer" onclick="window.location='{{ route('admin.jadwal.show', $jadwal) }}'">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->kereta->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->stasiun_awal }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->stasiun_akhir }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->jam_berangkat }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $jadwal->jam_sampai }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Rp {{ number_format($jadwal->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2">
                                    @if (auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.jadwal.edit', $jadwal) }}" onclick="event.stopPropagation()" class="text-indigo-600 hover:text-indigo-800 hover:underline">Edit</a>
                                        <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" method="POST" class="inline" onsubmit="event.stopPropagation(); return confirm('Hapus jadwal, Tuan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="event.stopPropagation()" class="text-red-600 hover:text-red-800 hover:underline">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-600">Tidak ada jadwal ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-200">
            {{ $jadwals->appends(['search' => $search, 'kereta_id' => $kereta_id])->links('pagination::tailwind') }}
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

        document.querySelectorAll('table tr.cursor-pointer').forEach(row => {
            row.addEventListener('click', (e) => {
                if (!e.target.closest('a') && !e.target.closest('button')) {
                    showNotification('Menuju detail jadwal.', 'info');
                }
            });
        });

        const searchForm = document.querySelector('form');
        if (searchForm) {
            searchForm.addEventListener('submit', () => {
                const searchValue = document.querySelector('input[name="search"]').value;
                const keretaValue = document.querySelector('select[name="kereta_id"]').value;
                if (searchValue || keretaValue) {
                    showNotification(`Mencari: ${searchValue || 'Semua'} di kereta ${keretaValue ? 'terpilih' : 'Semua'}.`, 'info');
                }
            });
        }

        const tambahButton = document.querySelector('a[href="{{ route('admin.jadwal.create') }}"]');
        if (tambahButton) {
            tambahButton.addEventListener('click', () => {
                showNotification('Menuju tambah jadwal.', 'info');
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