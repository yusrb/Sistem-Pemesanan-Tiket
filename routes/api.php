<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\KeretaController;
use App\Http\Controllers\Api\GerbongController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\PenumpangController;
use App\Http\Controllers\Api\PenumpangDashboardController;

// =======================
// RUTE AUTENTIKASI
// =======================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// =======================
// RUTE TERPROTEKSI (API)
// =======================
Route::middleware('auth:sanctum')->group(function () {

    // -----------------------
    // User (hanya admin)
    // -----------------------
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('keretas', KeretaController::class);
    });
    Route::apiResource('gerbongs', GerbongController::class);

        Route::apiResource('jadwals', JadwalController::class);

    // -----------------------
    // Profile (semua role)
    // -----------------------
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // -----------------------
    // Pemesanan (semua role)
    // -----------------------
    Route::apiResource('pemesanans', PemesananController::class);
    Route::post('/pemesanans/{pemesanan}/cancel', [PemesananController::class, 'cancel']);
    Route::post('/pemesanans/{detailPemesanan}/check-in', [PemesananController::class, 'checkIn'])->middleware('role:petugas');
    Route::post('/pemesanans/{detailPemesanan}/complete', [PemesananController::class, 'complete'])->middleware('role:petugas');

    // -----------------------
    // Penumpang (hanya penumpang)
    // -----------------------
    Route::middleware('role:penumpang')->group(function () {
        Route::apiResource('penumpangs', PenumpangController::class);
        Route::get('/dashboard', [PenumpangDashboardController::class, 'index']);
    });

});
