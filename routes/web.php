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
use App\Http\Controllers\UserProfileController; 

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

// **BARU/DITAMBAHKAN KEMBALI:** LANGKAH 1: Tampilkan Jadwal Tayang untuk Film yang dipilih
// Route ini harus di luar 'middleware('auth')' agar user bisa melihat jadwal sebelum login.
// Nama rute yang dicari oleh tombol "Pesan Ticket": 'film.schedule'
Route::get('/film/{filmId}/schedule', [JadwalTayangController::class, 'getSchedulesForFilm'])
    ->name('film.schedule'); 


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


// --- 4. Rute Khusus User (Transaksi & Profil) - WAJIB LOGIN ---
Route::middleware('auth')->group(function () {
    
    // A. Rute Pemesanan Tiket (Lanjutan)
    
    // LANGKAH 2: Tampilkan halaman pemilihan kursi (WAJIB LOGIN)
    // URL: /user/pemesanan/{jadwalId}/select-seat
    Route::get('/user/pemesanan/{jadwalId}/select-seat', [PemesananController::class, 'selectSeat'])
        ->name('user.pemesanan.select_seat');
        
   
    // LANGKAH 3: Proses data kursi yang dipilih dan simpan transaksi (WAJIB LOGIN)
    // URL: /user/pemesanan/process
    
 Route::post('/user/pemesanan/process', [PemesananController::class, 'processPemesanan'])
        ->name('user.pemesanan.process');
        

    // B. Riwayat Pemesanan User
    
    Route::get('/user/history', [UserProfileController::class, 'history'])
        ->name('user.history');
        
    // Rute untuk melihat detail pemesanan (digunakan sebagai halaman checkout/pembayaran)
    Route::get('/user/pemesanan/{kode_pemesanan}', [UserProfileController::class, 'showPemesanan'])
        ->name('user.pemesanan.show');

        // Rute untuk memicu update status pembayaran (dari 'menunggu_pembayaran' ke 'paid')
    Route::post('/user/pemesanan/{kode_pemesanan}/confirm-payment', [PemesananController::class, 'confirmPayment'])
        ->name('user.pemesanan.confirmPayment');
        
});