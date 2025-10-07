<aside id="sidebar" class="bg-white border-r border-gray-100 w-60 z-40 hidden md:block flex-shrink-0">
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6 px-4">{{ $setting->nama_website }}</h1>
        <div class="space-y-2">
            <a href="{{ auth()->user()->role === 'petugas' ? route('petugas.dashboard') : route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') || request()->routeIs('petugas.dashboard') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">
               Beranda
            </a>

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.kereta.index') }}" class="{{ request()->routeIs('admin.kereta.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Kereta</a>
                <a href="{{ route('admin.gerbong.index') }}" class="{{ request()->routeIs('admin.gerbong.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Gerbong</a>
                <a href="{{ route('admin.jadwal.index') }}" class="{{ request()->routeIs('admin.jadwal.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Jadwal</a>
                <a href="{{ route('admin.user.index') }}" class="{{ request()->routeIs('admin.user.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Pengguna</a>
                <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Laporan</a>
            @elseif (auth()->user()->role === 'petugas')
                <a href="{{ route('petugas.pemesanan.index') }}" class="{{ request()->routeIs('petugas.pemesanan.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} block px-4 py-3 rounded-lg transition">Pemesanan</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 block px-4 py-3 rounded-lg transition w-full text-left">Logout</button>
            </form>
        </div>
    </div>
</aside>