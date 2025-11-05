<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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

Route::get('/index', function () {
    // Memanggil view 'resources/views/layouts/app.blade.php'
    return view('tasks.index');
});

// 🚀 ROUTE CRUD TASK BARU
// Ini akan membuat 7 jalur URL sekaligus yang terhubung ke TaskController
Route::resource('tasks', TaskController::class); 

