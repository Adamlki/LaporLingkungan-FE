<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Middleware\AdminMiddleware; // <-- TAMBAHKAN INI

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== RUTE PUBLIK (Bisa diakses siapa saja) =====
Route::get('/', [LaporanController::class, 'index'])->name('home');


// ===== RUTE PENGGUNA TERAUTENTIKASI (USER BIASA) =====
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard User
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Laporan oleh User
    Route::resource('laporan', LaporanController::class);
});


// ===== RUTE ADMIN (Terpisah dan dilindungi) =====
Route::middleware(['auth', AdminMiddleware::class])->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
         ->name('admin.dashboard');

    // (Tambahkan rute admin lainnya di sini jika perlu)
});


// ===== RUTE OTENTIKASI (Login, Register, dll) =====
require __DIR__.'/auth.php';