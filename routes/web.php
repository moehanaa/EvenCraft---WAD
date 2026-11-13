<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketCategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - EventCraft (Laravel 11)
| Modul Kategori Tiket & Benefit (Individu C)
|--------------------------------------------------------------------------
*/

// Redirect Halaman Utama
Route::get('/', function () {
    return auth()->check() ? redirect()->route('tickets.index') : redirect()->route('login');
});

// =========================================================================
// 1. RUTE AUTENTIKASI (Khusus Guest / Belum Login)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// =========================================================================
// 2. RUTE LOGOUT (Khusus Pengguna yang Sedang Login)
// =========================================================================
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =========================================================================
// 3. RUTE MODUL TIKET (Wajib Login / Protected by Auth Middleware)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Hak Akses: Admin & Role Ke-2 (Staff) bisa Lihat (Read), Tambah (Create), dan Ubah (Update)
    Route::resource('tickets', TicketCategoryController::class)->except(['destroy']);

    // Hak Akses Khusus: HANYA ADMIN yang Boleh Menghapus Data (Delete) via Role Middleware
    Route::delete('tickets/{ticket}', [TicketCategoryController::class, 'destroy'])
        ->name('tickets.destroy')
        ->middleware('role:admin');
});
