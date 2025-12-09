<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (FRONTEND ONLY)
|--------------------------------------------------------------------------
| Tugas file ini cuma mengantar pengunjung ke file View (.blade.php).
| Tidak boleh ada logika database atau middleware auth di sini.
| Keamanan (Auth) ditangani oleh JavaScript di setiap halaman.
*/

// 1. RUTE HALAMAN UTAMA (Redirect ke Login biar rapi)
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. RUTE AUTH (Bawaan Laravel Breeze)
require __DIR__.'/auth.php';


// 3. RUTE HALAMAN (Tanpa Middleware Auth Server-side)
// Kita pakai Route::view karena kita cuma mau buka file blade saja.

// Dashboard
Route::view('/dashboard', 'dashboard')->name('dashboard');

// Profil (Nanti logic-nya pakai JS juga)
Route::view('/profile', 'profile.edit')->name('profile.edit');


// === RUTE LAPORAN ===
// Perhatikan: Kita cuma membuka "Kulit" halamannya.
// Isinya nanti di-load pakai JS (Fetch API).

// Halaman List Laporan
Route::view('/laporan', 'laporan.index')->name('laporan.index');

// Halaman Buat Laporan Baru
Route::view('/laporan/create', 'laporan.create')->name('laporan.create');

// Halaman Edit Laporan (Kita butuh ID di URL biar JS bisa baca)
Route::get('/laporan/{id}/edit', function ($id) {
    return view('laporan.edit', ['id' => $id]);
})->name('laporan.edit');

// Halaman Baca Detail
Route::get('/baca-laporan/{id}', function ($id) {
    return view('laporan.show', ['id' => $id]);
})->name('laporan.baca');


// === RUTE ADMIN ===
// Admin juga sama, cuma view. Nanti JS yang cek apakah dia admin atau bukan.
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');