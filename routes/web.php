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

// ===== RUTE USER (Login Diperlukan) =====
Route::middleware(['auth'])->group(function () {
    
    // Dashboard & Profil (Biarkan seperti adanya)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === RUTE LAPORAN MANUAL (PENTING!) ===
    
    // 1. Form Create
    Route::get('/laporan/create', [LaporanController::class, 'create'])
        ->name('laporan.create'); // Nama rute untuk membuka form
    
    // 2. Proses Simpan (INI YANG MEMPERBAIKI ERROR ANDA)
    Route::post('/laporan', [LaporanController::class, 'store'])
        ->name('web.laporan.store'); // <--- Nama ini harus cocok dengan di form Blade
    
    // 3. Detail
   //Route::get('/laporan/{laporan}', [LaporanController::class, 'show'])
        //->name('laporan.show');
    Route::get('/baca-laporan/{id}', [LaporanController::class, 'show'])->name('laporan.baca');
    
    // 4. Update & Delete
    Route::get('/laporan/{laporan}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
    Route::put('/laporan/{laporan}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
});


// ===== RUTE ADMIN =====
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
         ->name('admin.dashboard');
});

require __DIR__.'/auth.php';