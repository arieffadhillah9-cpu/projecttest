<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\JadwalTayang; // Import Model JadwalTayang
use Carbon\Carbon;

class HomepageController extends Controller
{
    /**
     * Menampilkan daftar film yang sedang tayang untuk pengguna (User View).
     */
    public function index()
    {
        // 1. Ambil film yang sedang tayang (is_tayang = true)
        $films = Film::where('is_tayang', true)
                     ->orderBy('tanggal_rilis', 'desc')
                     ->get();

        // 2. Ambil semua Jadwal Tayang yang valid (belum lewat)
        // Memastikan hanya jadwal dari hari ini dan seterusnya yang diambil
        $validJadwal = JadwalTayang::where('tanggal', '>=', Carbon::today()->toDateString())
                                   ->orderBy('tanggal')
                                   ->orderBy('jam_mulai')
                                   ->get();

        // 3. Ambil Tanggal Unik yang Tersedia
        // Digunakan untuk menampilkan tombol filter tanggal di dashboard
        $availableDates = $validJadwal->pluck('tanggal')->unique()->values();

        // 4. Ambil Jam Tayang Unik yang Tersedia
        // Digunakan untuk menampilkan tombol filter jam di dashboard
        $availableTimes = $validJadwal->pluck('jam_mulai')->unique()->values()->map(function ($time) {
            // Ubah format jam dari "H:i:s" menjadi "H:i"
            return Carbon::parse($time)->format('H:i');
        });

        // Mengarahkan ke view 'index.blade.php' (tampilan User)
        return view('index', compact('films', 'availableDates', 'availableTimes'));
    }

    /**
     * Endpoint API untuk memfilter film berdasarkan tanggal dan jam.
     * Dipanggil oleh AJAX dari index.blade.php
     */
    public function filterFilms(Request $request)
    {
        $query = Film::where('is_tayang', true);

        // Filter film yang memiliki jadwal tayang sesuai tanggal yang dipilih
        if ($request->has('date') && $request->date) {
            $date = $request->date; // Format YYYY-MM-DD
            $query->whereHas('jadwalTayang', function ($q) use ($date) {
                $q->where('tanggal', $date);
            });
        }

        // Filter film yang memiliki jadwal tayang sesuai jam yang dipilih
        if ($request->has('time') && $request->time) {
            $time = $request->time . ':00'; // Tambahkan detik untuk format H:i:s
            $query->whereHas('jadwalTayang', function ($q) use ($time) {
                $q->where('jam_mulai', $time);
            });
        }
        
        // Tambahkan filter untuk jadwal yang valid (tidak lewat)
        // Ini memastikan film yang ditampilkan minimal tayang pada hari ini
        $query->whereHas('jadwalTayang', function ($q) {
            $q->where('tanggal', '>=', Carbon::today()->toDateString());
        });


        $films = $query->orderBy('tanggal_rilis', 'desc')->get();

        // Karena ini adalah endpoint API, kita kembalikan JSON
        // 'view' berisi HTML daftar film yang sudah di-render, siap disisipkan ke DOM
        return response()->json([
            'films' => $films,
            'view' => view('components.film_list', compact('films'))->render() 
        ]);
    }
}