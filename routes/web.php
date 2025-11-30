<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\JadwalTayangController;
use App\Http\Controllers\PemesananController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController; 
// [TAMBAHAN] Import HomepageController untuk tampilan User/Frontend
use App\Http\Controllers\HomepageController; 


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Route Publik/Umum ---

// [PERUBAHAN KRUSIAL] Route utama sekarang mengarah ke HomepageController@index
// Ini akan menampilkan halaman utama (index.blade.php) dengan daftar film yang sedang tayang
Route::get('/', [HomepageController::class, 'index'])->name('homepage');

// [TAMBAHAN] ROUTE HALAMAN KONTAK
// Route ini merespons permintaan GET ke URL /contacts
// dan mengarahkan ke view 'resources/views/contacts.blade.php'
Route::get('/contacts', function () {
    return view('contacts'); 
})->name('contacts');


// ROUTE DASHBOARD PUBLIK (SUDAH DIARAHKAN KE CONTROLLER)
Route::get('/dashboard', [HomeController::class, 'dashboardUser'])->name('dashboard.user');

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
    
    // a. Dashboard Admin 
    Route::get('/dashboardmin', [DashboardController::class, 'index'])->name('dashboardmin'); 

    // b. Resource Routes CRUD (sudah benar, menggunakan FilmController untuk CRUD admin)
    Route::resource('film', FilmController::class);
    Route::resource('studio', StudioController::class);
    Route::resource('jadwal', JadwalTayangController::class);

    // c. Resource Pemesanan Khusus Admin (index, show, destroy)
    Route::resource('pemesanan', PemesananController::class)->only(['index', 'show', 'destroy']);

    // d. Rute Khusus Update Status (admin)
    Route::put('pemesanan/{pemesanan}/status', [PemesananController::class, 'updateStatus'])->name('pemesanan.update.status');
});


// --- 4. Rute Khusus User (Transaksi) ---
// Route ini tetap di luar grup admin
Route::post('pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');