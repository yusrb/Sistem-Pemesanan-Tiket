<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemesanan - Manajemen Tiket Kereta</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Pemesanan
            </h1>
            <p class="text-gray-600">Tanggal Cetak: {{ now()->format('d F Y') }}</p>
            <p class="text-gray-600">Periode: {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d/m/Y') : 'Semua' }} - {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d/m/Y') : 'Semua' }}</p>
            <p class="text-gray-600">Status: {{ $status ? ucfirst($status) : 'Semua Status' }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-indigo-50 rounded-lg">
                <p class="text-sm font-semibold text-gray-600">Total Pemesanan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $total_pemesanan }}</p>
            </div>
            <div class="p-4 bg-indigo-50 rounded-lg">
                <p class="text-sm font-semibold text-gray-600">Total Penumpang</p>
                <p class="text-2xl font-bold text-gray-900">{{ $total_penumpang }}</p>
            </div>
            <div class="p-4 bg-indigo-50 rounded-lg">
                <p class="text-sm font-semibold text-gray-600">Total Harga</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($total_harga, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jadwal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Penumpang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pemesanans as $pemesanan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pemesanan->user->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pemesanan->jadwal->kereta->nama }}: {{ $pemesanan->jadwal->stasiun_awal }} - {{ $pemesanan->jadwal->stasiun_akhir }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pemesanan->jumlah_penumpang }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($pemesanan->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pemesanan->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-600">Tidak ada pemesanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
