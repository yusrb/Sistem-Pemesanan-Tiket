@php
    $isAdmin = request()->is('admin/*');
@endphp

@extends($isAdmin ? 'layouts.app' : 'layouts.app_penumpang')

@section('judul', 'Detail Pemesanan - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-12 max-w-7xl">
    <div class="mb-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Pemesanan #{{ $pemesanan->id }}</h1>
                    <p class="text-gray-500 text-sm">Diperbarui pada {{ now()->format('d F Y') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500" data-clock>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Terakhir diperbarui: {{ now()->format('H:i') }} WIB</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-lg transition-all duration-300 hover:shadow-xl border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 font-medium">Pengguna:</span>
                    <span class="text-gray-900">{{ $pemesanan->user->nama }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 font-medium">Jadwal:</span>
                    <span class="text-gray-900">{{ $pemesanan->jadwal->kereta->nama_kereta }} ({{ $pemesanan->jadwal->stasiun_awal }} - {{ $pemesanan->jadwal->stasiun_akhir }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 font-medium">Tanggal:</span>
                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($pemesanan->tanggal)->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 font-medium">Jumlah Penumpang:</span>
                    <span class="text-gray-900">{{ $pemesanan->jumlah_penumpang }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 font-medium">Total Harga:</span>
                    <span class="text-gray-900">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 font-medium">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pemesanan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($pemesanan->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($pemesanan->status) }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 font-medium">Batas Pembayaran:</span>
                    <span class="text-gray-900">{{ $pemesanan->expired_at ? \Carbon\Carbon::parse($pemesanan->expired_at)->format('d F Y H:i') : '-' }} WIB</span>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-900 mb-6">Detail Penumpang</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Penumpang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gerbong</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        @if (auth()->user()->role === 'petugas')
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($pemesanan->detailPemesanans as $detail)
                        <tr class="hover:bg-gray-50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->penumpang->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->penumpang->user->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->gerbong->kode_gerbong }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->kode }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $detail->status === 'booked' ? 'bg-yellow-100 text-yellow-800' : ($detail->status === 'checked_in' || $detail->status === 'boarded' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($detail->status) }}
                                </span>
                            </td>
                            @if (auth()->user()->role === 'petugas')
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($detail->status === 'booked')
                                        <form action="{{ route('pemesanan.checkIn', $detail) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">Check-In</button>
                                        </form>
                                    @elseif ($detail->status === 'checked_in' || $detail->status === 'boarded')
                                        <form action="{{ route('pemesanan.complete', $detail) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 font-medium transition-colors duration-200">Selesai</button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <a href="{{ route('pemesanan.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-all duration-200 text-center font-medium">Kembali</a>
            @if ($pemesanan->status === 'pending' && (auth()->user()->role !== 'penumpang' || $pemesanan->user_id === auth()->id()))
                <form action="{{ route('pemesanan.cancel', $pemesanan) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-all duration-200 font-medium">Batalkan</button>
                </form>
            @endif
            @if ($pemesanan->status === 'paid')
                <a href="{{ route('pemesanan.print', $pemesanan) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 text-center font-medium">Cetak Struk</a>
            @endif
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
            notification.className = `fixed top-6 right-6 p-4 rounded-xl shadow-2xl z-50 transition-all duration-300 transform translate-x-full max-w-sm border`;
            const colors = {
                success: 'bg-green-50 text-green-800 border-green-200',
                error: 'bg-red-50 text-red-800 border-red-200',
                warning: 'bg-yellow-50 text-yellow-800 border-yellow-200',
                info: 'bg-indigo-50 text-indigo-800 border-indigo-200'
            };
            notification.classList.add(...colors[type].split(' '));
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">${message}</span>
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