@php
$isAdmin = request()->is('admin/*');
@endphp

@extends($isAdmin ? 'layouts.app' : 'layouts.app_penumpang')

@section('judul', 'Tambah Penumpang - Manajemen Tiket Kereta')

@section('konten')
<main class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Tambah Penumpang
                </h1>
                <p class="text-gray-600">Tambah penumpang baru, <span class="font-semibold">{{ now()->format('d F Y') }}</span></p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="flex items-center space-x-2 text-sm text-gray-600" data-clock>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Terakhir diperbarui: {{ now()->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl p-6 shadow-lg hover:bg-gray-50 transition">
        <form action="{{ route('penumpang.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-600 mb-2">Nama</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="w-full border {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-200' }} rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500" required>
                    @error('nama')
                        <div class="flex items-center gap-1 mt-1 text-red-600 text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Mohon maaf, Tuan, nama {{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="nik" class="block text-sm font-semibold text-gray-600 mb-2">NIK</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" class="w-full border {{ $errors->has('nik') ? 'border-red-500' : 'border-gray-200' }} rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500" maxlength="16" required>
                    @error('nik')
                        <div class="flex items-center gap-1 mt-1 text-red-600 text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Mohon maaf, Tuan, NIK {{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-600 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-200' }} rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500" required>
                    @error('email')
                        <div class="flex items-center gap-1 mt-1 text-red-600 text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Mohon maaf, Tuan, email {{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="no_telepon" class="block text-sm font-semibold text-gray-600 mb-2">No. Telepon (Opsional)</label>
                    <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" class="w-full border {{ $errors->has('no_telepon') ? 'border-red-500' : 'border-gray-200' }} rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500" maxlength="15">
                    @error('no_telepon')
                        <div class="flex items-center gap-1 mt-1 text-red-600 text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Mohon maaf, Tuan, nomor telepon {{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('penumpang.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition inline-block text-center">Kembali</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition transform hover:scale-105">Simpan</button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
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
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'success' ? 
                        '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>' : 
                        '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                    }
                </svg>
                <span class="text-sm">${message}</span>
            </div>
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 500);
        }, 4000);
    }

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', () => {
            showNotification('Menyimpan penumpang, Tuan', 'info');
        });
    }

    @if (session('sukses'))
        showNotification('{{ session('sukses') }}', 'success');
    @elseif (session('error'))
        showNotification('Mohon maaf, Tuan, {{ session('error') }}', 'error');
    @elseif ($errors->any())
        @foreach ($errors->all() as $error)
            showNotification('Mohon maaf, Tuan, {{ $error }}', 'error');
        @endforeach
    @endif
});
</script>
@endsection