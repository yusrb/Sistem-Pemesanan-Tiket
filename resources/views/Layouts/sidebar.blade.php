<aside id="sidebar" class="bg-white border-r border-gray-100 w-60 z-40 hidden md:block flex-shrink-0">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6 px-4">Tikecs</h1>
        <div class="space-y-2">
            <a href="{{ auth()->user()->role === 'petugas' ? route('petugas.dashboard') : route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') || request()->routeIs('petugas.dashboard') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">
               Beranda
            </a>

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('kereta.index') }}" class="{{ request()->routeIs('kereta.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Kereta</a>
                <a href="{{ route('gerbong.index') }}" class="{{ request()->routeIs('gerbong.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Gerbong</a>
                <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Jadwal</a>
                <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Pengguna</a>
                <a href="{{ route('pemesanan.index') }}" class="{{ request()->routeIs('pemesanan.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Pemesanan</a>
                <a href="{{ route('penumpang.index') }}" class="{{ request()->routeIs('penumpang.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Penumpang</a>
                <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Laporan</a>
            @elseif (auth()->user()->role === 'petugas')
                <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Jadwal</a>
                <a href="{{ route('pemesanan.index') }}" class="{{ request()->routeIs('pemesanan.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Pemesanan</a>
                <a href="{{ route('penumpang.index') }}" class="{{ request()->routeIs('penumpang.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Penumpang</a>
            @endif
        </div>
    </div>
</aside>
