<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KAI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #e3f2fd 0%, #f8f9ff 100%); 
            font-family: 'Inter', sans-serif;
        }
        .glass { 
            backdrop-filter: blur(20px); 
            background: rgba(255, 255, 255, 0.9); 
            border: 1px solid rgba(59, 130, 246, 0.2); 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .input-focus { transition: all 0.2s ease-in-out; }
        .input-focus:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4">
    <main class="w-full max-w-md space-y-8">
        <div class="text-center">
            <i class="fas fa-train text-6xl text-blue-600 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Masuk ke KAI</h2>
            <p class="text-gray-600">Selamat datang kembali</p>
        </div>
        <form class="space-y-6 glass rounded-2xl p-8" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                    <input id="nik" name="nik" type="text" required value="{{ old('nik') }}" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 input-focus @error('nik') border-red-500 @enderror" placeholder="Masukkan NIK">
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" name="password" type="password" required class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 input-focus @error('password') border-red-500 @enderror" placeholder="Masukkan Password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 shadow-md">Masuk</button>
        </form>
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
        <div class="text-center">
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">Belum punya akun? Daftar</a>
        </div>
    </main>
</body>
</html>