<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SettingController;
use App\Http\Controllers\PetugasPemesananController;
use App\Http\Controllers\{AuthController, UserController, JadwalController, KeretaController, GerbongController, LaporanController, ProfileController, PemesananController, PenumpangController, AdminDashboardController, PetugasDashboardController, PenumpangDashboardController};

Route::get('/', function() {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            default => redirect()->route('penumpangs.dashboard'),
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
Route::prefix('admin')
    ->middleware(['auth','role:admin'])
    ->group(function() {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Kereta & Gerbong
        Route::resource('/kereta', KeretaController::class)
            ->parameters(['kereta' => 'kereta'])
            ->names('admin.kereta');

        Route::resource('/gerbong', GerbongController::class)
            ->names('admin.gerbong');

        // Jadwal
        Route::resource('/jadwal', JadwalController::class)->names('admin.jadwal');

        // User
        Route::resource('/user', UserController::class)
            ->names('admin.user');

        // Penumpang
        Route::resource('/penumpang', PenumpangController::class)
            ->names('admin.penumpang');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('admin.laporan.cetak');

        // Setting
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    });


// Petugas routes
Route::middleware(['role:petugas,admin'])->group(function() {
    Route::get('/petugas/dashboard', [PetugasDashboardController::class, 'index'])->name('petugas.dashboard');

    Route::resource('pemesanan', PemesananController::class)->except(['destroy']);
});

Route::prefix('petugas')->middleware(['auth', 'role:petugas'])->name('petugas.')->group(function () {
    Route::resource('pemesanan', PetugasPemesananController::class)->only(['index', 'show']);
    Route::post('pemesanan/{pemesanan}/validate', [PetugasPemesananController::class, 'validatePemesanan'])->name('pemesanan.validate');
    Route::post('pemesanan/{detailPemesanan}/check-in', [PetugasPemesananController::class, 'checkIn'])->name('pemesanan.checkIn');
    Route::post('pemesanan/{detailPemesanan}/complete', [PetugasPemesananController::class, 'complete'])->name('pemesanan.complete');
});

// Penumpang routes
Route::middleware(['auth'])
    ->prefix('penumpang')
    ->group(function () {
        Route::get('dashboard', [PenumpangDashboardController::class, 'index'])
            ->name('penumpangs.dashboard');

        Route::resource('pemesanan', PemesananController::class);
        Route::get('pemesanan/{pemesanan}/print', [PemesananController::class, 'print'])->name('pemesanan.print');

        Route::post('pemesanan/{pemesanan}/cancel', [PemesananController::class, 'cancel'])
            ->name('pemesanan.cancel');
        Route::get('gerbongs/{jadwal}', [PemesananController::class, 'getGerbongs'])
    ->name('gerbongs.get');


        Route::resource('penumpang', PenumpangController::class);
    });