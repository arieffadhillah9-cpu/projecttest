<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\JadwalTayangController;
use App\Http\Controllers\PemesananController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\HomepageController; 
use App\Http\Controllers\UserProfileController; // BARU: Untuk halaman riwayat user

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. Route Publik/Umum ---

// Route utama sekarang mengarah ke HomepageController@index
Route::get('/', [HomepageController::class, 'index'])->name('homepage');

// Route Halaman Kontak
Route::get('/contacts', function () {
    return view('contacts'); 
})->name('contacts');

// ROUTE DASHBOARD PUBLIK
Route::get('/dashboard', [HomeController::class, 'dashboardUser'])->name('dashboard.user');

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
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    
    // a. Dashboard Admin 
    Route::get('/dashboardmin', [DashboardController::class, 'index'])->name('dashboardmin'); 

    // b. Resource Routes CRUD 
    Route::resource('film', FilmController::class);
    Route::resource('studio', StudioController::class);
    Route::resource('jadwal', JadwalTayangController::class);

    // c. Resource Pemesanan Khusus Admin (index, show, destroy)
    Route::resource('pemesanan', PemesananController::class)->only(['index', 'show', 'destroy']);

    // d. Rute Khusus Update Status (admin)
    Route::put('pemesanan/{pemesanan}/status', [PemesananController::class, 'updateStatus'])->name('pemesanan.update.status');
});


// --- 4. Rute Khusus User (Transaksi & Profil) ---
Route::middleware('auth')->group(function () {
    // Route Pemesanan (Hanya POST store yang perlu di luar admin)
    Route::post('pemesanan', [PemesananController::class, 'store'])->name('pemesanan.store');

    // BARU: Riwayat Pemesanan User
    // Asumsi Anda akan membuat controller dan view untuk melihat riwayat
    Route::get('/user/history', [UserProfileController::class, 'history'])
    ->name('user.history');
Route::get('/user/pemesanan/{kode_pemesanan}', [UserProfileController::class, 'showPemesanan'])
    ->name('user.pemesanan.show');
});