<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use Illuminate\Support\Facades\Storage; // Pastikan ini ter-import untuk manipulasi file

class FilmController extends Controller
{
    /**
     * Menampilkan daftar semua film.
     */
    public function index()
    {
        $films = Film::orderBy('id', 'desc')->get();
        // [PERUBAHAN] Mengarahkan ke view 'admin.film.index'
        return view('admin.film.index', compact('films')); 
    }

    /**
     * Menampilkan formulir untuk membuat film baru.
     */
    public function create()
    {
        // [PERUBAHAN] Mengarahkan ke view 'admin.film.create'
        return view('admin.film.create');
    }

    /**
     * Menyimpan data film baru dari formulir ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255|unique:films,judul',
            'deskripsi' => 'required',
            'durasi_menit' => 'required|integer|min:1',
            'sutradara' => 'nullable|string|max:100',
            'genre' => 'required|string|max:100',
            'tanggal_rilis' => 'required|date',
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'is_tayang' => 'nullable|boolean',
        ]);
        
        // 2. Proses Upload Gambar (Perbaikan: Hanya simpan path relatif)
        if ($request->hasFile('poster_path')) {
            // Simpan file ke direktori 'posters' menggunakan disk 'public'.
            // $uploadedPath akan berisi path relatif, contoh: posters/namafileunik.jpg
            $uploadedPath = $request->file('poster_path')->store('posters', 'public');
            
            // [PERBAIKAN KRUSIAL] HANYA simpan path relatif ke database. 
            // Jangan tambahkan 'storage/' di sini, karena 'asset('storage/')' di view sudah menambahkannya.
            $validatedData['poster_path'] = $uploadedPath; 
        }

        // 3. Simpan ke Database
        Film::create($validatedData);

        // 4. Redirect dan Tampilkan Pesan Sukses
        return redirect()->route('admin.film.index')
                             ->with('success', 'Film baru berhasil ditambahkan!');
    }
    
    /**
     * Menampilkan detail spesifik dari satu film.
     */
    public function show(Film $film)
    {
        // [PERUBAHAN] Mengarahkan ke view 'admin.film.show'
        return view('admin.film.show', compact('film'));
    }

    /**
     * Menampilkan formulir untuk mengedit film.
     */
    public function edit(Film $film)
    {
        // [PERUBAHAN] Mengarahkan ke view 'admin.film.edit'
        return view('admin.film.edit', compact('film'));
    }

    /**
     * Memperbarui data film yang sudah ada di database.
     */
    public function update(Request $request, Film $film)
    {
        // 1. Validasi Data
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255|unique:films,judul,' . $film->id, 
            'deskripsi' => 'required',
            'durasi_menit' => 'required|integer|min:1',
            'sutradara' => 'nullable|string|max:100',
            'genre' => 'required|string|max:100',
            'tanggal_rilis' => 'required|date',
            // Gunakan 'sometimes' agar validasi gambar hanya berjalan jika ada file baru
            'poster_path' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'is_tayang' => 'nullable|boolean',
        ]);
        
        // 2. Proses Upload Gambar (untuk update)
        if ($request->hasFile('poster_path')) {
            
            // Hapus gambar lama (jika ada) dari disk 'public'
            if ($film->poster_path) {
                // Perbaikan: Hapus file menggunakan path yang tersimpan di DB
                Storage::disk('public')->delete($film->poster_path);
            }

            // Simpan gambar baru
            $uploadedPath = $request->file('poster_path')->store('posters', 'public');
            // [PERBAIKAN KRUSIAL] HANYA simpan path relatif
            $validatedData['poster_path'] = $uploadedPath; 
        } else {
            // Jika tidak ada file baru, hapus 'poster_path' dari validatedData 
            // agar path lama tidak ditimpa oleh NULL (jika kolom di DB nullable).
            // Namun karena kita hanya menyimpan path jika ada, ini sebenarnya tidak perlu,
            // tapi memastikan is_tayang atau data lain tetap terupdate.
            // Untuk amannya, kita memastikan poster_path tidak ada di validatedData jika tidak ada file baru.
            // Karena kita menggunakan array $validatedData, ini sudah ditangani (jika tidak ada file baru, 'poster_path' tidak ada).
        }


        // 3. Update data film
        $film->update($validatedData);

        // 4. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.film.index')
                             ->with('success', 'Film berhasil diupdate!');
    }
    
    /**
     * Menghapus film dari database.
     */
    public function destroy(Film $film)
    {
        // 1. Hapus gambar terkait dari storage
        if ($film->poster_path) {
            // Perbaikan: Hapus langsung menggunakan Storage::disk('public')
            Storage::disk('public')->delete($film->poster_path);
        }
        
        // 2. Hapus data dari database
        if ($film->delete()) {
            return redirect()->route('admin.film.index')
                             ->with('success', 'Film berhasil dihapus.');
        }

        return redirect()->route('admin.film.index')
                             ->with('error', 'Gagal menghapus film.');
    }
    public function getSchedulesForFilm($filmId)
{
    $film = Film::findOrFail($filmId);

    // Ambil semua tanggal unik dari jadwal film ini
    $availableDates = JadwalTayang::where('film_id', $filmId)
        ->orderBy('tanggal', 'asc')
        ->pluck('tanggal')
        ->unique();

    // Ambil jadwal berdasarkan tanggal yang dipilih (atau default tanggal pertama)
    $selectedDate = request()->get('date', $availableDates->first());

    $jadwal_tayang = JadwalTayang::where('film_id', $filmId)
        ->where('tanggal', $selectedDate)
        ->orderBy('jam_mulai', 'asc')
        ->get();

    return view('user.pemesanan.booking_schedule', [
        'film' => $film,
        'availableDates' => $availableDates,
        'selectedDate' => $selectedDate,
        'jadwal_tayang' => $jadwal_tayang
    ]);
}
}