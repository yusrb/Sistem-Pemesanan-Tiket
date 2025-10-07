@extends('layouts.app')

@section('judul', 'Detail Pemesanan - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8 border-b border-gray-200 pb-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Detail Pemesanan #{{ $pemesanan->id }}
                </h1>
                <p class="text-gray-500 mt-1">
                    Validasi atau kelola pemesanan — <span class="font-semibold">{{ now()->format('d F Y') }}</span>
                </p>
            </div>
            <div class="mt-4 sm:mt-0 text-sm text-gray-500" data-clock>
                Terakhir diperbarui: {{ now()->format('H:i') }} WIB
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-8 hover:shadow-lg transition">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2 text-gray-700 text-sm leading-relaxed">
                <p><span class="font-semibold">Pengguna:</span> {{ $pemesanan->user->nama }}</p>
                <p><span class="font-semibold">Jadwal:</span> {{ $pemesanan->jadwal->kereta->nama_kereta }} ({{ $pemesanan->jadwal->stasiun_awal }} – {{ $pemesanan->jadwal->stasiun_akhir }})</p>
                <p><span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($pemesanan->tanggal)->format('d/m/Y') }}</p>
                <p><span class="font-semibold">Jumlah Penumpang:</span> {{ $pemesanan->jumlah_penumpang }}</p>
                <p><span class="font-semibold">Total Harga:</span> Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                <p><span class="font-semibold">Status:</span> {{ ucfirst($pemesanan->status) }}</p>
                <p><span class="font-semibold">Batas Pembayaran:</span> {{ $pemesanan->expired_at_formatted }} WIB</p>
            </div>

            @if ($pemesanan->status === 'pending')
                <div>
                    <form action="{{ route('petugas.pemesanan.validate', $pemesanan) }}" method="POST" class="space-y-3">
                        @csrf
                        <label for="status" class="block text-sm font-semibold text-gray-600">Validasi Pemesanan</label>
                        <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="paid">Setujui (Paid)</option>
                            <option value="cancelled">Tolak (Cancelled)</option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-xs">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition">
                            Simpan Validasi
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <h2 class="text-xl font-semibold text-gray-900 mt-10 mb-4">Detail Penumpang</h2>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama Penumpang</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">NIK</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Gerbong</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Kode Tiket</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($pemesanan->detailPemesanans as $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $detail->penumpang->nama }}</td>
                            <td class="px-6 py-4">{{ $detail->penumpang->nik }}</td>
                            <td class="px-6 py-4">{{ $detail->gerbong->kode_gerbong }}</td>
                            <td class="px-6 py-4">{{ $detail->kode }}</td>
                            <td class="px-6 py-4">{{ ucfirst($detail->status) }}</td>
                            <td class="px-6 py-4">
                                @if ($detail->status === 'booked')
                                    <form action="{{ route('petugas.pemesanan.checkIn', $detail) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-indigo-600 hover:underline">Check-In</button>
                                    </form>
                                @elseif ($detail->status === 'checked_in' || $detail->status === 'boarded')
                                    <form action="{{ route('petugas.pemesanan.complete', $detail) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:underline">Selesai</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            <a href="{{ route('petugas.pemesanan.index') }}"
               class="inline-block bg-gray-700 text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                Kembali
            </a>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                timeZone: 'Asia/Jakarta'
            });
            document.querySelector('[data-clock]').textContent =
                `Terakhir diperbarui: ${timeString} WIB`;
        }
        updateClock();
        setInterval(updateClock, 60000);

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className =
                `fixed top-6 right-6 px-6 py-3 rounded-lg shadow-md text-sm transition transform translate-x-full`;
            const colors = {
                success: 'bg-green-50 text-green-700 border border-green-200',
                error: 'bg-red-50 text-red-700 border border-red-200',
                warning: 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                info: 'bg-indigo-50 text-indigo-700 border border-indigo-200'
            };
            notification.classList.add(...colors[type].split(' '));
            notification.textContent = message;

            document.body.appendChild(notification);
            setTimeout(() => notification.classList.remove('translate-x-full'), 50);
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
