@php
    $isAdmin = request()->is('admin/*');
@endphp

@extends($isAdmin ? 'layouts.app' : 'layouts.app_penumpang')

@section('judul', 'Edit Pemesanan - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                              a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Pemesanan
                </h1>
                <p class="text-gray-600">Perbarui pemesanan,
                    <span class="font-semibold">{{ now()->format('d F Y') }}</span>
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="flex items-center space-x-2 text-sm text-gray-600" data-clock>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0
                              9 9 0 0118 0z"/>
                    </svg>
                    <span>Terakhir diperbarui: {{ now()->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-lg hover:bg-gray-50 transition">
        <form action="{{ route('pemesanan.update', $pemesanan) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="kereta_id" id="kereta_id"
                   value="{{ old('kereta_id', $pemesanan->jadwal->kereta_id ?? '') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jadwal_id" class="block text-sm font-semibold text-gray-600 mb-2">Jadwal</label>
                    <select name="jadwal_id" id="jadwal_id"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                        <option value="">Pilih Jadwal</option>
                        @foreach ($jadwals as $jadwal)
                            <option value="{{ $jadwal->id }}"
                                    data-kereta-id="{{ $jadwal->kereta->id }}"
                                    {{ old('jadwal_id', $pemesanan->jadwal_id) == $jadwal->id ? 'selected' : '' }}>
                                {{ $jadwal->kereta->nama }}:
                                {{ $jadwal->stasiun_awal }} - {{ $jadwal->stasiun_akhir }}
                                ({{ $jadwal->jam_berangkat }} - {{ $jadwal->jam_sampai }})
                            </option>
                        @endforeach
                    </select>
                    @error('jadwal_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-600 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                           value="{{ old('tanggal', optional($pemesanan->tanggal)->format('Y-m-d')) }}"
                           min="{{ now()->format('Y-m-d') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    @error('tanggal')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-600 mb-2">Status</label>
                    <select name="status" id="status"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                        <option value="pending" {{ old('status', $pemesanan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ old('status', $pemesanan->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ old('status', $pemesanan->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Penumpang & Gerbong</label>
                    <div id="penumpang-list" class="space-y-4">
                        @foreach ($pemesanan->detailPemesanans as $i => $detail)
                            <div class="penumpang-item flex flex-col sm:flex-row gap-4">
                                <div class="flex-1">
                                    <select name="penumpang_ids[]" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                                        <option value="">Pilih Penumpang</option>
                                        @foreach ($passengers as $passenger)
                                            <option value="{{ $passenger->id }}"
                                                {{ old('penumpang_ids.' . $i, $detail->penumpang_id == $passenger->id || ($passenger->is_user && $detail->penumpang->nik == Auth::user()->nik) ? 'selected' : '') }}>
                                                {{ $passenger->nama }} {{ $passenger->is_user ? '(Anda)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('penumpang_ids.' . $i)
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex-1">
                                    <select name="gerbong_ids[]" class="gerbong-select w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                                        <option value="">Pilih Gerbong</option>
                                        @foreach ($gerbongs as $gerbong)
                                            <option value="{{ $gerbong->id }}"
                                                    data-kereta-id="{{ $gerbong->kereta_id }}"
                                                    {{ old('gerbong_ids.' . $i, $detail->gerbong_id) == $gerbong->id ? 'selected' : '' }}>
                                                {{ $gerbong->kode_gerbong }} ({{ $gerbong->jumlah_kursi }} kursi)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gerbong_ids.' . $i)
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="button" class="remove-penumpang bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Hapus</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-penumpang"
                            class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Tambah Penumpang
                    </button>
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-4 bg-red-50 text-red-700 p-4 rounded-lg border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-6 flex gap-3">
                <a href="{{ route('pemesanan.index') }}"
                   class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition inline-block text-center">
                    Kembali
                </a>
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition transform hover:scale-105">
                    Perbarui Pemesanan
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const jadwalSelect = document.getElementById('jadwal_id');
    const keretaInput = document.getElementById('kereta_id');
    const penumpangList = document.getElementById('penumpang-list');
    const addPenumpang = document.getElementById('add-penumpang');

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
        notification.innerHTML = `<span class="text-sm">${message}</span>`;
        document.body.appendChild(notification);
        setTimeout(() => notification.classList.remove('translate-x-full'), 100);
        setTimeout(() => { notification.classList.add('translate-x-full'); setTimeout(() => notification.remove(), 500); }, 4000);
    }

    function updateGerbongSelect(selectElement, gerbongs) {
        selectElement.innerHTML = '<option value="">Pilih Gerbong</option>';
        if (gerbongs && gerbongs.length > 0) {
            gerbongs.forEach(g => {
                const option = document.createElement('option');
                option.value = g.id;
                option.textContent = `${g.kode_gerbong} (${g.jumlah_kursi} kursi)`;
                selectElement.appendChild(option);
            });
        } else {
            selectElement.innerHTML = '<option value="">Tidak ada gerbong tersedia</option>';
        }
    }

    async function fetchGerbongs(keretaId) {
        if (!keretaId) {
            const selects = penumpangList.querySelectorAll('.gerbong-select');
            selects.forEach(s => updateGerbongSelect(s, []));
            return;
        }
        try {
            const url = "{{ route('gerbongs.get', ':id') }}".replace(':id', keretaId);
            const res = await fetch(url);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const gerbongs = await res.json();
            const selects = penumpangList.querySelectorAll('.gerbong-select');
            selects.forEach(s => updateGerbongSelect(s, gerbongs));
        } catch (e) {
            showNotification('Gagal memuat gerbong', 'error');
        }
    }

    jadwalSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const keretaId = selectedOption ? selectedOption.getAttribute('data-kereta-id') : '';
        keretaInput.value = keretaId || '';
        fetchGerbongs(keretaId);
    });

    addPenumpang.addEventListener('click', () => {
        const item = document.createElement('div');
        item.className = 'penumpang-item flex flex-col sm:flex-row gap-4';
        item.innerHTML = `
            <div class="flex-1">
                <select name="penumpang_ids[]" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    <option value="">Pilih Penumpang</option>
                    @foreach ($passengers as $passenger)
                        <option value="{{ $passenger->id }}">{{ $passenger->nama }} {{ $passenger->is_user ? '(Anda)' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <select name="gerbong_ids[]" class="gerbong-select w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500">
                    <option value="">Pilih Gerbong</option>
                </select>
            </div>
            <button type="button" class="remove-penumpang bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Hapus</button>
        `;
        penumpangList.appendChild(item);
        const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];
        const keretaId = selectedOption ? selectedOption.getAttribute('data-kereta-id') : '';
        if (keretaId) fetchGerbongs(keretaId);
    });

    penumpangList.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-penumpang')) {
            const items = penumpangList.querySelectorAll('.penumpang-item');
            if (items.length > 1) {
                e.target.closest('.penumpang-item').remove();
                showNotification('Penumpang dihapus', 'warning');
            } else {
                showNotification('Minimal satu penumpang diperlukan', 'error');
            }
        }
    });

    const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];
    const keretaId = selectedOption ? selectedOption.getAttribute('data-kereta-id') : '';
    if (keretaId) fetchGerbongs(keretaId);
});
</script>
@endsection