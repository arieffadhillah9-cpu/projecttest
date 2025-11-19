<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\JadwalTayangController;
use App\Http\Controllers\PemesananController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Route Publik/Umum ---

Route::get('/', function () {
    return view('welcome');
});

// ROUTE DASHBOARD PUBLIK (INI YANG ANDA MAKSUD)
Route::get('/dashboard', function () {
    // Memanggil view 'resources/views/layout/dashboard.blade.php'
    return view('layout.dashboard');
});

Route::get('/app', function () {
    // Memanggil view 'resources/views/layouts/app.blade.php'
    return view('layout.app');
});


// Route CRUD Task (Publik/Umum)
Route::resource('tasks', TaskController::class); 


// --- 2. Route Otentikasi Laravel UI ---
Auth::routes();

// Route yang akan diakses setelah login sukses (default)
Route::get('/home', [HomeController::class, 'index'])->name('home');


// --- 3. Kelompok Route ADMIN (Prefix 'admin' dan Name 'admin.') ---
// SEMUA route di bawah ini sekarang harus dipanggil dengan awalan 'admin.'
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    
    // a. Dashboard Admin (Mengatasi ERROR: route('admin.dashboard') not defined)
    // URL: /admin
    // Name: admin.dashboard
    Route::get('/dashboardmin', [DashboardController::class, 'index'])->name('dashboardmin'); 

    // b. Resource Routes CRUD (Sekarang memiliki nama admin.film.index, admin.studio.index, dll.)
    // URL: /admin/film, /admin/studio, /admin/jadwal
    Route::resource('film', FilmController::class);
    Route::resource('studio', StudioController::class);
    Route::resource('jadwal', JadwalTayangController::class);

    // c. Resource Pemesanan Khusus Admin (index, show, destroy)
    // URL: /admin/pemesanan
    Route::resource('pemesanan', PemesananController::class)->only(['index', 'show', 'destroy']);

    // d. Rute Khusus Update Status (admin)
    // URL: PUT /admin/pemesanan/{pemesanan}/status
    // Name: admin.pemesanan.update.status
    Route::put('pemesanan/{pemesanan}/status', [PemesananController::class, 'updateStatus'])->name('pemesanan.update.status');
});


// --- 4. Rute Khusus User (Transaksi) ---
// Route ini tetap di luar grup admin agar dapat diakses oleh fitur transaksi user
Route::post('pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');