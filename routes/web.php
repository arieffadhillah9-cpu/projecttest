<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/app', function () {
    // Memanggil view 'resources/views/layouts/app.blade.php'
    return view('layout.app');
});