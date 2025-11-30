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
     * Show the PUBLIC dashboard (Rute: /dashboard).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function index()
    {
        // 1. Ambil Film Utama (Film Hero)
        $filmHero = Film::with('jadwalTayang')->inRandomOrder()->first();

        // 2. Ambil Daftar Semua Film (untuk grid di bawah)
        $films = Film::with('jadwalTayang.studio')->latest()->get();

        // 3. Ambil Jadwal untuk Hari Ini
        $today = Carbon::today();
        
        $jadwalHariIni = $filmHero 
            ? $filmHero->jadwalTayang()
                        ->whereDate('waktu_tayang', $today)
                        ->orderBy('waktu_tayang')
                        ->get() 
            : collect(); // Jika tidak ada film hero, kembalikan koleksi kosong

        return view('layout.dashboard', compact('filmHero', 'films', 'jadwalHariIni'));
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