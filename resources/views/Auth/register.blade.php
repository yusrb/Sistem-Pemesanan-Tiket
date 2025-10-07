<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - KAI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-100 to-white flex items-center justify-center px-4 py-8">
    <div class="absolute inset-0 opacity-5">
        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="relative w-full max-w-md">
        <div id="errorMessage" class="hidden fixed top-4 right-4 bg-red-500 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-lg transition-all duration-500 transform translate-x-full">
            Kredensial tidak sesuai. Silakan coba lagi.
        </div>

        <div class="text-center mb-4">
            <div class="flex justify-center mb-2">
                <i class="fas fa-train h-20 text-blue-600"></i>
            </div>
            <div class="flex justify-center mb-2">
                <img src="{{ asset('storage/' . ($setting->logo ?? 'images/logo-tikecs.png')) }}" 
                    alt="Logo Tikecs" class="h-28 w-auto" />
            </div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $setting->nama_website}}</h1>
            <p class="text-gray-600 text-sm">Buat akun baru</p>
        </div>


        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-4">
                @csrf
                <div class="text-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Akun Baru</h2>
                    <p class="text-gray-600 text-sm">Silakan masukkan informasi Anda untuk mendaftar</p>
                </div>

                @if ($errors->has('nama') || $errors->has('nik') || $errors->has('no_telepon') || $errors->has('password'))
                    <div id="errorMessage" class="fixed top-4 right-4 bg-red-500 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-lg transition-all duration-500 transform translate-x-0 opacity-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <div class="relative">
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('nama') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan Nama Lengkap" required>
                        <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <input type="text" id="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan Email" required>
                       
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                    <div class="relative">
                        <input type="text" id="nik" name="nik" value="{{ old('nik') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('nik') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan NIK" required>
                        <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700">No Telepon (Opsional)</label>
                    <div class="relative">
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('no_telepon') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan No Telepon">
                        <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m-1 14h7m-7-6h7m-7 6h7"/>
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" class="w-full pl-10 pr-10 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan Password" required>
                        <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <svg id="eyeIcon" class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full pl-10 pr-10 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Konfirmasi Password" required>
                        <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>

                <button type="submit" id="registerButton" class="w-full bg-blue-700 text-white py-2 rounded font-medium hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="registerButtonText">Daftar</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-700 hover:text-blue-800">Sudah punya akun? Masuk</a>
            </div>

            <div class="mt-6 text-center text-xs text-gray-600">
                <p>© {{ date('Y') }} KAI. Hak cipta dilindungi.</p>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const registerForm = document.getElementById('registerForm');
        const errorMessage = document.getElementById('errorMessage');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.innerHTML = type === 'text' ? 
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>` :
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        });

        @if ($errors->has('nama') || $errors->has('nik') || $errors->has('no_telepon') || $errors->has('password'))
            errorMessage.textContent = "{{ $errors->first() }}";
            errorMessage.classList.remove('hidden', 'translate-x-full');
            errorMessage.classList.add('translate-x-0', 'opacity-100');
            setTimeout(() => {
                errorMessage.classList.remove('translate-x-0', 'opacity-100');
                errorMessage.classList.add('translate-x-full');
                setTimeout(() => errorMessage.classList.add('hidden'), 500);
            }, 3000);
        @endif

        @if (session('success'))
            errorMessage.textContent = "{{ session('success') }}";
            errorMessage.classList.remove('bg-red-500');
            errorMessage.classList.add('bg-green-500');
            errorMessage.classList.remove('hidden', 'translate-x-full');
            errorMessage.classList.add('translate-x-0', 'opacity-100');
            setTimeout(() => {
                errorMessage.classList.remove('translate-x-0', 'opacity-100');
                errorMessage.classList.add('translate-x-full');
                setTimeout(() => errorMessage.classList.add('hidden'), 500);
            }, 3000);
        @endif
    </script>
</body>
</html>