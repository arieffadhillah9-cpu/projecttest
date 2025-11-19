<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Konstruktor: Melindungi Controller ini. 
     * Hanya pengguna terautentikasi (auth) yang dapat mengakses.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman dashboard utama admin.
     * Route yang memanggil method ini adalah route('admin.dashboard')
     * View Path: resources/views/admin/layout/dashboard.blade.php
     */
    public function index()
    {
        // Pastikan view 'admin.layout.dashboard' sudah ada di folder resources/views/admin/layout/
        return view('admin.layout.dashboard');
    }
}