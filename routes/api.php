<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjualController;

// Public
Route::post('/register', [AuthController::class, 'register']); // self-register seller
// Route::get('/login', [AuthController::class, 'tampillogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/penjual', [PenjualController::class, 'index']);
Route::get('/penjual/{id}', [PenjualController::class, 'show']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Admin-only: bisa buat user (admin/seller) via registerByAdmin & kelola semua penjual
    Route::middleware('role:admin')->group(function () {
        Route::post('/register/admin', [AuthController::class, 'registerByAdmin']);
        // Route::post('/penjual', [PenjualController::class, 'store']);
        Route::put('/penjual/{id}', [PenjualController::class, 'update']);
        Route::delete('/penjual/{id}', [PenjualController::class, 'destroy']);
    });

    // Seller-only: lihat & update kios miliknya
    Route::middleware('role:seller')->group(function () {
        // Route::get('/penjual/me', [PenjualController::class, 'myKios']);
        Route::put('/penjual/me', [PenjualController::class, 'updateMyKios']);
        // seller TIDAK boleh create / delete penjual
    });
});
