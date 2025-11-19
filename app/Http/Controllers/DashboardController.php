<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Konstruktor: Middleware sudah ada di routes/web.php, 
     * jadi ini opsional dan bisa dihapus.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth'); 
    // }

    /**
     * Menampilkan halaman dashboard utama admin.
     * Route yang memanggil method ini adalah route('admin.dashboard')
     * View Path yang dipanggil: resources/views/admin/dashboardmin.blade.php
     */
    public function index()
    {
        // Panggil view dengan nama file yang benar: 'admin.dashboardmin'
        return view('admin.dashboardmin');
    }
}