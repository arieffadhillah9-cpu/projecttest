<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini adalah tempat Anda dapat mendaftarkan route API untuk aplikasi Anda.
| Route ini dimuat oleh RouteServiceProvider dalam group yang sudah
| memiliki middleware "api" secara default.
|
*/

// Contoh route yang akan dapat diakses di URL: /api/user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route API kustom Anda selanjutnya akan ditulis di bawah sini.
// Contoh: Route::resource('pemesanan', PemesananController::class);