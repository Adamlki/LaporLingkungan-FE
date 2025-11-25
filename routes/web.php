<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== RUTE PUBLIK =====
Route::get('/', [LaporanController::class, 'index'])->name('home');


// ===== RUTE USER (Hapus 'verified' agar tidak dicegat) =====
Route::middleware(['auth'])->group(function () {
    
    // Dashboard User (Hapus duplikatnya tadi)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
    // CRUD Laporan
    // Nonaktifkan resource otomatis untuk 'show'
Route::resource('laporan', LaporanController::class)->except(['show']);

// Buat rute manual dengan nama yang SANGAT BEDA
Route::get('/cek-laporan/{id}', [LaporanController::class, 'show'])->name('laporan.cek_detail');
});


// ===== RUTE ADMIN =====
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
         ->name('admin.dashboard');
});

require __DIR__.'/auth.php';