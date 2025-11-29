<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\JadwalTayangController;
use App\Http\Controllers\PemesananController; 
use App\Http\Controllers\HomeController; // Dipakai untuk Dashboard Publik
use App\Http\Controllers\DashboardController; // Dipakai untuk Dashboard Admin

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Route Publik/Umum ---

Route::get('/', function () {
    return view('welcome');
});

// ROUTE DASHBOARD PUBLIK
// URL: /dashboard
// Name: dashboard.public (Saya beri nama agar bisa dipanggil nanti)
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.public');

Route::get('/app', function () {
    // Memanggil view 'resources/views/layouts/app.blade.php'
    return view('layout.app');
});


// Route CRUD Task (Publik/Umum)
Route::resource('tasks', TaskController::class); 

// Halaman Pemilihan Kursi (Membutuhkan Auth)
// URL: /pesan/kursi/{jadwal}
Route::get('/pesan/kursi/{jadwal}', [PemesananController::class, 'seatPicker'])->middleware('auth')->name('pemesanan.seatpicker');


// --- 2. Route Otentikasi Laravel UI ---
Auth::routes();

// Route yang akan diakses setelah login sukses (default)
Route::get('/home', [HomeController::class, 'index'])->name('home');


// --- 3. Kelompok Route ADMIN (Prefix 'admin' dan Name 'admin.') ---

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    
    // a. Dashboard Admin (URL: /admin/dashboardmin, Name: admin.dashboardmin)
    Route::get('/dashboardmin', [DashboardController::class, 'index'])->name('dashboardmin'); 

    // b. Resource Routes CRUD 
    Route::resource('film', FilmController::class);
    Route::resource('studio', StudioController::class);
    Route::resource('jadwal', JadwalTayangController::class);

    // c. Resource Pemesanan Khusus Admin 
    Route::resource('pemesanan', PemesananController::class)->only(['index', 'show', 'destroy']);

    // d. Rute Khusus Update Status (admin)
    Route::put('pemesanan/{pemesanan}/status', [PemesananController::class, 'updateStatus'])->name('pemesanan.update.status');
});


// --- 4. Rute Khusus User (Transaksi) ---
// Route ini tetap di luar grup admin
Route::post('pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');