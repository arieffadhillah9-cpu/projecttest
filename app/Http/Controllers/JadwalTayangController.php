<?php

namespace App\Http\Controllers;

use App\Models\JadwalTayang;
use App\Models\Film;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JadwalTayangController extends Controller
{
    /**
     * Display a listing of the resource (Admin View).
     */
    public function index()
    {
        // Ambil semua jadwal tayang, urutkan berdasarkan tanggal dan jam
        $jadwalTayangs = JadwalTayang::with(['film', 'studio'])
                                     ->orderBy('tanggal', 'asc')
                                     ->orderBy('jam_mulai', 'asc')
                                     ->paginate(10); 

        return view('admin.jadwal.index', compact('jadwalTayangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil SEMUA Studio
        $studios = Studio::all(); 
        
        // Ambil HANYA Film yang sedang tayang (is_tayang = 1)
        $films = Film::where('is_tayang', 1)->orderBy('judul')->get();

        // Jika tidak ada film yang sedang tayang, beri peringatan
        if ($films->isEmpty()) {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Tidak ada film yang sedang tayang. Harap tandai film sebagai "Sedang Tayang" terlebih dahulu.');
        }

        return view('admin.jadwal.create', compact('films', 'studios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'tanggal' => 'required|date|after_or_equal:today', // Jadwal harus hari ini atau di masa depan
            'jam_mulai' => 'required|date_format:H:i', // Format 24 jam (e.g., 14:30)
            'harga' => 'required|integer|min:10000',
        ]);
        
        // Cek Konflik: Pastikan tidak ada jadwal lain di studio yang sama pada jam yang sama
        $existingSchedule = JadwalTayang::where('studio_id', $validatedData['studio_id'])
            ->where('tanggal', $validatedData['tanggal'])
            ->where('jam_mulai', $validatedData['jam_mulai'])
            ->first();

        if ($existingSchedule) {
            return back()->withInput()->with('error', 'Gagal: Studio ini sudah memiliki jadwal tayang pada tanggal dan jam tersebut.');
        }

        JadwalTayang::create($validatedData);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal tayang baru berhasil ditambahkan!');
    }
     

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalTayang $jadwal)
    {
        $studios = Studio::all();
        $films = Film::where('is_tayang', 1)
                     ->orWhere('id', $jadwal->film_id)
                     ->orderBy('judul')
                     ->get();

        return view('admin.jadwal.edit', [
            'jadwalTayang' => $jadwal, // boleh alias di sini
            'films' => $films,
            'studios' => $studios,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalTayang $jadwalTayang)
    {
        $validatedData = $this->validateJadwal($request, $jadwalTayang->id);

        // Cek Konflik Sambil Mengecualikan Jadwal Saat Ini ($jadwalTayang->id)
        $existingSchedule = JadwalTayang::where('studio_id', $validatedData['studio_id'])
            ->where('tanggal', $validatedData['tanggal'])
            ->where('jam_mulai', $validatedData['jam_mulai'])
            ->where('id', '!=', $jadwalTayang->id) // PENTING: Kecualikan ID Jadwal yang sedang diedit
            ->first();

        if ($existingSchedule) {
            return back()->withInput()->with('error', 'Gagal: Studio ini sudah memiliki jadwal tayang pada tanggal dan jam tersebut.');
        }

        $jadwalTayang->update($validatedData);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal tayang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalTayang $jadwalTayang)
    {
        // Catatan: Jika ada pemesanan yang terkait, 
        // mekanisme onDelete('cascade') di migrasi akan menghapus tiket terkait.
        // Jika tidak, Anda perlu implementasi logika yang lebih ketat.
        $jadwalTayang->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal tayang berhasil dihapus.');
    }
    
    /**
     * Reusable validation logic.
     */
    protected function validateJadwal(Request $request, $id = null)
    {
        return $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            // Pastikan tanggal tidak di masa lalu.
            'tanggal' => 'required|date|after_or_equal:today', 
            'jam_mulai' => 'required|date_format:H:i',
            'harga' => 'required|integer|min:10000',
        ]);
    }

    // --- FUNGSI BARU UNTUK USER FRONTEND ---
    /**
     * Mengambil dan menampilkan jadwal tayang yang tersedia untuk film tertentu.
     * @param int $filmId ID dari Film
     * @return \Illuminate\View\View
     */
    public function getSchedulesForFilm($filmId)
    {
        // 1. Ambil semua jadwal yang valid untuk film ini, diurutkan
        $jadwal_tayang = JadwalTayang::where('film_id', $filmId)
                                     // Pastikan jadwal belum lewat (tanggal harus >= hari ini)
                                     ->where('tanggal', '>=', now()->toDateString())
                                     ->orderBy('tanggal')
                                     ->orderBy('jam_mulai')
                                     ->get();

        // 2. Ambil tanggal-tanggal unik yang tersedia
        // Menggunakan pluck('tanggal') karena nama kolomnya 'tanggal'
        $availableDates = $jadwal_tayang->pluck('tanggal')->unique()->values();

        // 3. Ambil detail film (opsional, tapi berguna untuk tampilan)
        $film = Film::find($filmId);

        // Pastikan film ditemukan dan ada jadwalnya
        if (!$film) {
             abort(404, 'Film tidak ditemukan.');
        }
        
        // Mengirimkan data yang diperlukan ke view.
        // Asumsi view ini dipanggil dari suatu route seperti: 
        // Route::get('film/{film}/schedule', [JadwalTayangController::class, 'getSchedulesForFilm'])->name('film.schedule');
        return view('booking_schedule', compact('jadwal_tayang', 'availableDates', 'film'));
    }
    // ----------------------------------------
}