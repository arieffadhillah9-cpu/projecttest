<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studio; // JANGAN LUPA IMPORT MODEL STUDIO

class StudioController extends Controller
{
    /**
     * INDEX: Menampilkan daftar semua studio.
     */
    public function index()
    {
        $studios = Studio::orderBy('id', 'desc')->get();
        return view('admin.studio.index', compact('studios'));
    }

    /**
     * CREATE: Menampilkan formulir untuk membuat studio baru.
     */
    public function create()
    {
        return view('admin.studio.create');
    }

    /**
     * STORE: Menyimpan data studio baru dari formulir ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100|unique:studios,nama',
            'kapasitas' => 'required|integer|min:1',
            'tipe_layar' => 'nullable|string|in:2D Standard,IMAX,Dolby Atmos,Premiere',
        ]);

        Studio::create($validatedData);

        return redirect()->route('admin.studio.index')
                         ->with('success', 'Studio berhasil ditambahkan!');
    }

    /**
     * SHOW: Menampilkan detail studio (Opsional).
     */
    public function show(Studio $studio)
    {
        // Dalam kasus Studio, show biasanya tidak terlalu diperlukan,
        // tapi kita biarkan dulu sesuai resource route.
        return view('admin.studio.show', compact('studio'));
    }

    /**
     * EDIT: Menampilkan formulir untuk mengedit studio.
     */
    public function edit(Studio $studio)
    {
        return view('admin.studio.edit', compact('studio'));
    }

    /**
     * UPDATE: Menyimpan perubahan data studio.
     */
    public function update(Request $request, Studio $studio)
    {
        $validatedData = $request->validate([
            // Tambahkan pengecualian untuk studio yang sedang diedit (Rule unik)
            'nama' => 'required|string|max:100|unique:studios,nama,' . $studio->id,
            'kapasitas' => 'required|integer|min:1',
            'tipe_layar' => 'nullable|string|in:2D Standard,IMAX,Dolby Atmos,Premiere',
        ]);

        $studio->update($validatedData);

        return redirect()->route('admin.studio.index')
                         ->with('success', 'Studio berhasil diupdate!');
    }

    /**
     * DESTROY: Menghapus studio.
     */
    public function destroy(Studio $studio)
    {
        $studio->delete();

        return redirect()->route('admin.studio.index')
                         ->with('success', 'Studio berhasil dihapus!');
    }
}
