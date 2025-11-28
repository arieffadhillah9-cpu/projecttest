<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film; // <-- HARUS ADA MODEL INI!

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware 'auth' dihapus dari sini agar dashboard publik bisa diakses.
        // Hanya route '/home' yang memerlukan login.
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Tampilan Dashboard User (Memuat data film).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboardUser()
    {
        // Ambil semua film yang sedang tayang (asumsi kolom is_tayang = 1)
        $films = Film::where('is_tayang', 1) 
                       ->latest() 
                       ->get();

        // Kirim data film ke view 'layout.dashboard'
        return view('layout.dashboard', compact('films'));
    }
}