<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjualController;

// Public
Route::post('/register', [AuthController::class, 'register']); // self-register seller //DONEEEE //MARKED
// Route::get('/login', [AuthController::class, 'tampillogin']);
Route::post('/login', [AuthController::class, 'login']); //DONEEEE
Route::get('/penjual', [PenjualController::class, 'index']); //DONEEEE
Route::get('/penjual/{id}', [PenjualController::class, 'show']); //DONEEEE
Route::get('/penjual/detail/{lokasi}', [PenjualController::class, 'details']); //DONEEEE
Route::get('/denah', [PenjualController::class, 'getDenahSVG']); //DONEEEE

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); //DONEEEE
    Route::get('/user/profile', [AuthController::class, 'profile']);

    // Admin-only: bisa buat user (admin/seller) via registerByAdmin & kelola semua penjual
    Route::middleware('role:admin')->group(function () {
        Route::post('/register/admin', [AuthController::class, 'registerByAdmin']); //DONEEEE
        // Route::post('/penjual', [PenjualController::class, 'store']);
        Route::put('/penjual/{id}', [PenjualController::class, 'update']);
        Route::delete('/penjual/{id}', [PenjualController::class, 'destroy']);

        // DENAH SVG
        Route::post('/update-denah', [PenjualController::class, 'updateDenahSVG']); //DONEEEE
    });

    // Seller-only: lihat & update kios miliknya
    Route::middleware('role:seller')->group(function () {
        // Route::get('/penjual/me', [PenjualController::class, 'myKios']);
        Route::put('/penjual/me', [PenjualController::class, 'updateMyKios']);
        // seller TIDAK boleh create / delete penjual
    });
});
