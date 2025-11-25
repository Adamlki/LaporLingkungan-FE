<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaporanApiController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rute API yang membutuhkan otentikasi (token)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Logika Logout (menghapus token saat ini)
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::apiResource('laporan', LaporanApiController::class);
});
