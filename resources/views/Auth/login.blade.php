<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KAI</title>
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
            <div class="flex justify-center items-center mb-2">
                <i class="fas fa-train h-20 text-blue-600"></i>
            </div>
            <div class="flex justify-center mb-2">
                <img src="{{ asset('storage/' . ($setting->logo ?? 'images/logo-tikecs.png')) }}" 
                    alt="Logo Tikecs" class="h-28 w-auto" />
            </div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $setting->nama_website }}</h1>
            <p class="text-gray-600 text-sm">Selamat datang kembali</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-4">
                @csrf
                <div class="text-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Masuk ke Akun Anda</h2>
                    <p class="text-gray-600 text-sm">Silakan masukkan kredensial Anda untuk melanjutkan</p>
                </div>

                @if ($errors->has('email'))
                    <div id="errorMessage" class="fixed top-4 right-4 bg-red-500 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-lg transition-all duration-500 transform translate-x-0 opacity-100">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-blue-300' }} rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan Email" required>
                        <svg class="w-5 h-5 text-blue-700 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
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

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-blue-300 rounded" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                    </div>
                    <a href="#" class="text-sm text-blue-700 hover:text-blue-800">Lupa kata sandi?</a>
                </div>

                <button type="submit" id="loginButton" class="w-full bg-blue-700 text-white py-2 rounded font-medium hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="loginButtonText">Masuk</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('register') }}" class="text-sm text-blue-700 hover:text-blue-800">Belum punya akun? Daftar</a>
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
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('errorMessage');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.innerHTML = type === 'text' ? 
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>` :
                `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        });

        @if ($errors->has('email'))
            errorMessage.textContent = "{{ $errors->first('email') }}";
            errorMessage.classList.remove('hidden', 'translate-x-full');
            errorMessage.classList.add('translate-x-0', 'opacity-100');
            setTimeout(() => {
                errorMessage.classList.remove('translate-x-0', 'opacity-100');
                errorMessage.classList.add('translate-x-full');
                setTimeout(() => errorMessage.classList.add('hidden'), 500);
            }, 3000);
        @endif

        @if (session('error'))
            errorMessage.textContent = "{{ session('error') }}";
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