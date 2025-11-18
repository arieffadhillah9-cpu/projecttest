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
        return view('admin.film.index', compact('films'));
    }

    /**
     * Menampilkan formulir untuk membuat film baru.
     */
    public function create()
    {
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
            // Gunakan 'poster_path' sesuai dengan nama kolom DB dan nama input file
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'is_tayang' => 'nullable|boolean',
        ]);
        
       if ($request->hasFile('poster_path')) {
        // 1. Simpan file ke direktori 'posters' menggunakan disk 'public'.
        //    Ini akan menyimpan file ke: storage/app/public/posters/
        //    Jalur yang dikembalikan ($uploadedPath) adalah: posters/namafileunik.jpg
        $uploadedPath = $request->file('poster_path')->store('posters', 'public');
        
        // 2. Simpan jalur ke database. Kita perlu menambahkan 'storage/' di depan 
        //    agar sesuai dengan format yang sudah Anda gunakan (storage/posters/...).
        //    Jika Anda tidak ingin ada awalan 'storage/', cukup gunakan $uploadedPath.
        $validatedData['poster_path'] = 'storage/' . $uploadedPath; 
        }

        // 3. Simpan ke Database
        Film::create($validatedData);

        // 4. Redirect dan Tampilkan Pesan Sukses
        return redirect()->route('film.index')
                         ->with('success', 'Film baru berhasil ditambahkan!');
    }
    

    /**
     * Menampilkan detail spesifik dari satu film.
     */
    public function show(Film $film)
    {
        return view('admin.film.show', compact('film'));
    }

    /**
     * Menampilkan formulir untuk mengedit film.
     */
    public function edit(Film $film)
    {
        // Mengembalikan view 'film.edit' dan membawa data film yang akan diedit
        return view('admin.film.edit', compact('film'));
    }

    /**
     * Memperbarui data film yang sudah ada di database.
     */
    public function update(Request $request, Film $film)
    {
        // 1. Validasi Data
        $validatedData = $request->validate([
            // Mengecualikan judul film saat ini agar tidak ada error unique jika judul tidak diubah
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
            $uploadedPath = $request->file('poster_path')->store('public/posters');
            $validatedData['poster_path'] = str_replace('public/', 'storage/', $uploadedPath); 
        } else {
            // Jika tidak ada file baru, pastikan poster_path lama tetap ada (hanya update field lain)
            // Cek apakah user mengirim input hidden untuk menghapus gambar atau tidak (untuk kasus lain, kita biarkan saja)
            
            // Jika Anda ingin mempertahankan poster_path yang lama jika tidak ada upload baru:
            // Karena kita menggunakan $validatedData, jika poster_path tidak ada di request, ia tidak akan ada di validatedData.
            // Kita harus secara eksplisit memasukkannya kembali ke validatedData jika tidak di-upload.
            if ($request->method() === 'PUT' || $request->method() === 'PATCH') {
                unset($validatedData['poster_path']);
            }
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
            $oldPath = str_replace('storage/', 'public/', $film->poster_path);
            Storage::delete($oldPath); 
        }
        
        // 2. Hapus data dari database
        if ($film->delete()) {
            return redirect()->route('admin.film.index')
                             ->with('success', 'Film berhasil dihapus.');
        }

        return redirect()->route('admin.film.index')
                         ->with('error', 'Gagal menghapus film.');
    }
}