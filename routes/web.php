<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, UserController, JadwalController, KeretaController, GerbongController, LaporanController, ProfileController, PemesananController, PenumpangController, AdminDashboardController, PetugasDashboardController, PenumpangDashboardController};

Route::get('/', function() {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            default => redirect()->route('penumpang.dashboard'),
        };
    }
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile
Route::middleware('auth')->group(function() {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes
Route::middleware(['auth','role:admin'])->group(function() {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Kereta & Gerbong
    Route::resource('kereta', KeretaController::class);
    Route::resource('gerbong', GerbongController::class);

    // User
    Route::resource('user', UserController::class);

    // Jadwal, Pemesanan, Penumpang
    Route::resource('jadwal', JadwalController::class);
    Route::resource('pemesanan', PemesananController::class);
    Route::resource('penumpang', PenumpangController::class);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
});

Route::resource('jadwal', JadwalController::class)->except(['destroy'])->middleware(['auth', 'role:petugas,admin']);

// Petugas routes
Route::middleware(['auth','role:petugas'])->group(function() {
    Route::get('/petugas/dashboard', [PetugasDashboardController::class, 'index'])->name('petugas.dashboard');

    Route::resource('pemesanan', PemesananController::class)->except(['destroy']);
    Route::resource('penumpang', PenumpangController::class)->except(['destroy']);
});

// Penumpang routes
Route::middleware(['auth','role:penumpang'])->group(function() {
    Route::get('/penumpang/dashboard', [PenumpangDashboardController::class, 'index'])->name('penumpang.dashboard');
});
