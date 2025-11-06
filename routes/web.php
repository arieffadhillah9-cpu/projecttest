<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\JadwalTayangController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('layout.dashboard');
});

Route::get('/app', function () {
    // Memanggil view 'resources/views/layouts/app.blade.php'
    return view('layout.app');
});



//  ROUTE CRUD TASK BARU
// Ini akan membuat 7 jalur URL sekaligus yang terhubung ke TaskController
Route::resource('tasks', TaskController::class); 

Route::resource('film', FilmController::class);
Route::resource('studio', StudioController::class);
Route::resource('jadwal', JadwalTayangController::class);