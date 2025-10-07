@php
    $isAdmin = request()->is('admin/*');
@endphp

@extends($isAdmin ? 'layouts.app' : 'layouts.app_penumpang')

@section('judul', 'Daftar Pemesanan - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Daftar Pemesanan
                </h1>
                <p class="text-gray-600">Kelola pemesanan tiket, <span class="font-semibold">{{ now()->format('d F Y') }}</span></p>
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
            <form method="GET" action="{{ route('pemesanan.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pengguna atau stasiun..." class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <select name="status" class="border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition transform hover:scale-105">Cari</button>
            </form>
            @if (auth()->user()->role !== 'admin')
                <a href="{{ route('pemesanan.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 hover:bg-indigo-700 transition transform hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Tambah Pemesanan</span>
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kereta</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Penumpang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pemesanans as $pemesanan)
                        <tr class="hover:bg-indigo-50 transition cursor-pointer" onclick="window.location='{{ route('pemesanan.show', $pemesanan) }}'">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pemesanan->user->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pemesanan->jadwal->kereta->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pemesanan->jadwal->stasiun_awal }} - {{ $pemesanan->jadwal->stasiun_akhir }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($pemesanan->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pemesanan->jumlah_penumpang }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($pemesanan->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('pemesanan.edit', $pemesanan) }}" onclick="event.stopPropagation()" class="text-indigo-600 hover:text-indigo-800 hover:underline">Edit</a>
                                    <form action="{{ route('pemesanan.destroy', $pemesanan) }}" method="POST" class="inline" onsubmit="event.stopPropagation(); return confirm('Hapus pemesanan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="event.stopPropagation()" class="text-red-600 hover:text-red-800 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-600">Tidak ada pemesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pemesanans->links() }}
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
                    showNotification('Menuju detail pemesanan', 'info');
                }
            });
        });

        const searchForm = document.querySelector('form');
        if (searchForm) {
            searchForm.addEventListener('submit', () => {
                const searchValue = document.querySelector('input[name="search"]').value;
                const statusValue = document.querySelector('select[name="status"]').value;
                if (searchValue || statusValue) {
                    showNotification(`Mencari: ${searchValue || 'Semua'} di status ${statusValue || 'Semua'}`, 'info');
                }
            });
        }

        const tambahButton = document.querySelector('a[href="{{ route('pemesanan.create') }}"]');
        if (tambahButton) {
            tambahButton.addEventListener('click', () => {
                showNotification('Menuju tambah pemesanan', 'info');
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