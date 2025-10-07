<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul') - Tikecs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-white border-b py-3 border-gray-100 shadow-sm z-50 sticky top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('storage/' . ($setting->logo ?? 'images/logo-tikecs.png')) }}" 
                        alt="Logo" class="h-12 w-auto" />
                    <h1 class="text-xl font-bold text-gray-900">{{ $setting->nama_website }}</h1>
                </div>

                <nav class="hidden md:flex justify-center items-center space-x-4">
                    @auth
                        @if (auth()->user()->role === 'penumpang')
                            <a href="{{ route('penumpangs.dashboard') }}" 
                            class="{{ request()->routeIs('penumpangs.dashboard') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} px-4 py-2 rounded-lg transition">
                            Beranda
                            </a>
                            <a href="{{ route('pemesanan.index') }}" 
                            class="{{ request()->routeIs('pemesanan.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} px-4 py-2 rounded-lg transition">
                            Pemesanan
                            </a>
                            <a href="{{ route('penumpang.index') }}" 
                            class="{{ request()->routeIs('penumpang.*') ? 'text-indigo-600 font-semibold bg-indigo-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }} px-4 py-2 rounded-lg transition">
                            Penumpang
                            </a>
                        @endif
                    @endauth
                </nav>

                <div class="flex justify-end items-center space-x-4">
                    @auth
                        <div class="relative">
                            <button id="userMenuButton" 
                                    class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition">
                                <img class="w-9 h-9 rounded-full object-cover ring-2 ring-indigo-200" 
                                    src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://static.vecteezy.com/system/resources/previews/020/911/732/non_2x/profile-icon-avatar-icon-user-icon-person-icon-free-png.png' }}" 
                                    alt="User Avatar">
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ Auth::user()->role }}</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="userDropdown" 
                                class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-10 h-10 rounded-full object-cover ring-2 ring-indigo-200" 
                                            src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://static.vecteezy.com/system/resources/previews/020/911/732/non_2x/profile-icon-avatar-icon-user-icon-person-icon-free-png.png' }}" 
                                            alt="User Avatar">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->nama }}</div>
                                            <div class="text-xs text-gray-500">{{ Auth::user()->role }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" 
                                    class="block px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 transition">Profil User</a>
                                    <div class="border-t border-gray-100"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" 
                        class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 block px-4 py-2 rounded-lg transition">Login</a>
                        <a href="{{ route('register') }}" 
                        class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 block px-4 py-2 rounded-lg transition">Registrasi</a>
                    @endauth

                    <button id="mobileMenuButton" class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>


    <div class="flex min-h-screen">
        @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'petugas']))
            @include('layouts.sidebar')
        @endif
        <div class="flex-1">
            @yield('konten')
        </div>
    </div>

    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            const sidebar = document.querySelector('aside');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', () => {
                    userDropdown.classList.toggle('hidden');
                });
            }

            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', () => {
                    if (mobileMenu) {
                        mobileMenu.classList.toggle('hidden');
                    }
                    if (sidebar) {
                        sidebar.classList.toggle('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>