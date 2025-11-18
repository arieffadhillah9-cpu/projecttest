<?php

namespace App\Http\Controllers;

use App\Models\Kursi;
use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KursiController extends Controller
{
    /**
     * Tampilkan daftar Studio untuk dipilih dalam manajemen Kursi.
     */
    public function index()
    {
        $studios = Studio::withCount('kursis')->get();
        // View: kursi/index.blade.php (Menampilkan semua studio)
        return view('admin.kursi.index', compact('studios')); 
    }

    /**
     * Tampilkan denah kursi yang sudah ada untuk Studio tertentu.
     */
    public function show(Studio $studio)
    {
        $kursis = $studio->kursis()->orderBy('nomor_kursi')->get();
        // View: kursi/show.blade.php (Denah kursi)
        return view('admin.kursi.show', compact('studio', 'kursis'));
    }

    /**
     * Tampilkan form untuk men-generate kursi baru.
     */
    public function create(Studio $studio)
    {
        // View: kursi/create.blade.php (Form Generator)
        return view('admin.kursi.create', compact('studio'));
    }

    /**
     * Utility: Hapus kursi lama dan generate kursi baru untuk Studio ini.
     */
    public function store(Request $request, Studio $studio)
    {
        $request->validate([
            'rows_end' => 'required|string|max:1', // Contoh: 'F'
            'seats_per_row' => 'required|integer|min:1|max:50', // Contoh: 10
        ]);

        $rowsEnd = strtoupper($request->rows_end);
        $seatsPerRow = $request->seats_per_row;
        $startCharCode = ord('A');
        $endCharCode = ord($rowsEnd);

        if ($endCharCode > ord('Z') || $endCharCode < $startCharCode) {
            return back()->withErrors(['rows_end' => 'Baris harus berupa huruf tunggal (A-Z).']);
        }

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // 1. Hapus semua kursi lama di studio ini
            $studio->kursis()->delete();
            
            $newKursi = [];
            // 2. Loop untuk generate kursi baru (A1, A2, ..., B1, B2, ...)
            for ($i = $startCharCode; $i <= $endCharCode; $i++) {
                $row = chr($i);
                for ($j = 1; $j <= $seatsPerRow; $j++) {
                    $newKursi[] = [
                        'studio_id' => $studio->id,
                        'nomor_kursi' => $row . $j,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            // 3. Masukkan kursi baru ke database
            Kursi::insert($newKursi);

            DB::commit();
            return redirect()->route('admin.kursi.show', $studio)->with('success', 'Denah kursi berhasil digenerate ulang!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal men-generate kursi: ' . $e->getMessage());
        }
    }

    // Kursi individual jarang dihapus, namun bisa ditambahkan method destroy jika diperlukan
}
