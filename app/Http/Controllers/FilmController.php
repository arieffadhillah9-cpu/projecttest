<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film; // JANGAN LUPA IMPORT MODEL

class FilmController extends Controller
{
    // ... (index() method yang sudah kita bahas sebelumnya)

    /**
     
     * Menampilkan formulir untuk membuat film baru.
     */

    public function index()
    {
        $films = Film::orderBy('id', 'desc')->get();
        return view('film.index', compact('films'));
    }



    public function create()
    {
        // Langsung tampilkan view formulir
        return view('film.create');
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
            // 'poster_path' dan 'is_tayang' bisa diabaikan atau ditambahkan validasinya
        ]);

        

        // 2. Simpan ke Database (karena $fillable sudah diatur, ini akan bekerja!)
        Film::create($validatedData);

        // 3. Redirect dan Tampilkan Pesan Sukses
        return redirect()->route('film.index') // Redirect ke halaman daftar film
                         ->with('success', 'Film baru berhasil ditambahkan!');
    }

    // ... (method show, edit, update, destroy lainnya)

        /**
        * SHOW: Menampilkan detail spesifik dari satu film.
        * @param int $id
        */
        public function show(Film $film) // Laravel secara otomatis mencari film berdasarkan ID/route model binding
        {
            // Mengirim objek $film yang ditemukan ke resources/views/film/show.blade.php
            return view('film.show', compact('film'));
        }

        // ... (methods edit, update, destroy di bawah)
        public function destroy(Film $film)
        {
            // Cek apakah film berhasil dihapus
            if ($film->delete()) {
                // Redirect ke halaman index dengan pesan sukses
                return redirect()->route('film.index')
                                ->with('success', 'Film berhasil dihapus.');
            }

            // Jika gagal
            return redirect()->route('film.index')
                            ->with('error', 'Gagal menghapus film.');
        }
        
        public function edit(Film $film)
        {
            // Mengembalikan view 'film.edit' dan membawa data film yang akan diedit
            return view('film.edit', compact('film'));
        }
        public function update(Request $request, Film $film)
        {
            // Lakukan validasi data seperti yang Anda lakukan di method store()
            $request->validate([
                'judul' => 'required|string|max:255',
                'durasi_menit' => 'required|integer|min:1',
                'tanggal_rilis' => 'required|date',
                // Tambahkan validasi untuk kolom lainnya
            ]);

            // Update data film
            $film->update($request->all());

            // Redirect ke halaman index dengan pesan sukses
            return redirect()->route('film.index')
                            ->with('success', 'Film berhasil diupdate!');
        }
}