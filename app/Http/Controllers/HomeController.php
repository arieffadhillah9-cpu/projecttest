<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// --- FIX DUA BARIS INI ---
use App\Models\Film; // Memanggil model Film
use Carbon\Carbon;    // Memanggil library waktu Carbon

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // PENTING: Middleware 'auth' dihapus dari sini.
        // Jika route /dashboard ini adalah PUBLIC, maka middleware ini harus dihilangkan.
        // Jika Anda ingin user harus login untuk melihatnya, aktifkan baris di bawah:
        // $this->middleware('auth'); 
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
}