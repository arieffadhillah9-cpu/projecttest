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
        
        if ($request->hasFile('poster_path')) {
            // Simpan file ke direktori 'posters' menggunakan disk 'public'.
            $uploadedPath = $request->file('poster_path')->store('posters', 'public');
            
            // Simpan jalur dengan awalan 'storage/'
            $validatedData['poster_path'] = 'storage/' . $uploadedPath; 
        }

        // 3. Simpan ke Database
        Film::create($validatedData);

        // 4. Redirect dan Tampilkan Pesan Sukses
        // [PERUBAHAN] Mengarahkan ke rute 'admin.film.index'
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
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_tayang' => 'nullable|boolean',
        ]);
        
        // 2. Proses Upload Gambar (untuk update)
        if ($request->hasFile('poster_path')) {
            
            // Hapus gambar lama (jika ada)
            if ($film->poster_path) {
                // Konversi path yang tersimpan (storage/posters/...) ke format storage (public/posters/...) untuk penghapusan
                $oldPath = str_replace('storage/', 'public/', $film->poster_path);
                Storage::delete($oldPath); 
            }

            // Simpan gambar baru
            $uploadedPath = $request->file('poster_path')->store('posters', 'public');
            $validatedData['poster_path'] = 'storage/' . $uploadedPath; 
        } else {
            // Jika tidak ada file baru, hapus 'poster_path' dari validatedData agar path lama tidak ditimpa oleh NULL.
            unset($validatedData['poster_path']);
        }


        // 3. Update data film
        $film->update($validatedData);

        // 4. Redirect ke halaman index dengan pesan sukses
        // [PERUBAHAN] Mengarahkan ke rute 'admin.film.index'
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
            $oldPath = str_replace('storage/', 'public/', $film->poster_path);
            Storage::delete($oldPath); 
        }
        
        // 2. Hapus data dari database
        if ($film->delete()) {
            // [PERUBAHAN] Mengarahkan ke rute 'admin.film.index'
            return redirect()->route('admin.film.index')
                             ->with('success', 'Film berhasil dihapus.');
        }

        // [PERUBAHAN] Mengarahkan ke rute 'admin.film.index'
        return redirect()->route('admin.film.index')
                         ->with('error', 'Gagal menghapus film.');
    }
}