@extends('layouts.app')

@section('judul', 'Dashboard Petugas - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                    Dashboard Petugas
                </h1>
                <p class="text-gray-600">Ringkasan pemesanan hari ini, <span class="font-semibold">{{ now()->format('d F Y') }}</span></p>
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

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-indigo-50 rounded-lg shadow-lg hover:bg-indigo-100 transition">
            <p class="text-sm font-semibold text-gray-600">Total Pemesanan</p>
            <p class="text-2xl font-bold text-gray-900">{{ $total_pemesanan }}</p>
        </div>
        <div class="p-4 bg-indigo-50 rounded-lg shadow-lg hover:bg-indigo-100 transition">
            <p class="text-sm font-semibold text-gray-600">Total Penumpang</p>
            <p class="text-2xl font-bold text-gray-900">{{ $total_penumpang }}</p>
        </div>
        <div class="p-4 bg-indigo-50 rounded-lg shadow-lg hover:bg-indigo-100 transition">
            <p class="text-sm font-semibold text-gray-600">Total Harga</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($total_harga, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pemesanan Terbaru Hari Ini</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pengguna</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jadwal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Penumpang</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pemesanans as $pemesanan)
                            <tr class="hover:bg-indigo-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pemesanan->user->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pemesanan->jadwal->kereta->nama }}: {{ $pemesanan->jadwal->stasiun_awal }} - {{ $pemesanan->jadwal->stasiun_akhir }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pemesanan->jumlah_penumpang }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($pemesanan->status) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('petugas.pemesanan.show', $pemesanan) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">Detail</a>
                                    <a href="{{ route('pemesanan.edit', $pemesanan) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline ml-2">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-600">Tidak ada pemesanan hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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

        @if (session('sukses'))
            showNotification('{{ session('sukses') }}', 'success');
        @elseif (session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endsection