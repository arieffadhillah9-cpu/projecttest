<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\JadwalTayang;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    // ===============================================
    //           ADMIN CRUD PEMESANAN (P2.7)
    // ===============================================

    /**
     * ADMIN: Menampilkan daftar semua Pemesanan.
     */
    public function indexAdmin()
    {
        $pemesanans = Pemesanan::with('jadwalTayang.film', 'user')
                            ->orderByDesc('created_at')
                            ->paginate(20);
                            
        return view('admin.pemesanan.index', compact('pemesanans'));
    }

    /**
     * ADMIN: Menampilkan detail Pemesanan.
     */
    public function showAdmin(Pemesanan $pemesanan)
    {
        $pemesanan->load('jadwalTayang.film', 'user');
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    /**
     * ADMIN: Mengupdate status Pemesanan (Misal: dari 'pending' ke 'paid').
     */
    public function updateAdmin(Request $request, Pemesanan $pemesanan)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,paid,cancelled',
        ]);

        $pemesanan->update([
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        return redirect()->route('admin.pemesanan.show', $pemesanan)->with('success', 'Status pemesanan berhasil diperbarui.');
    }

    /**
     * ADMIN: Menghapus Pemesanan.
     */
    public function destroyAdmin(Pemesanan $pemesanan)
    {
        $pemesanan->delete();
        return redirect()->route('admin.pemesanan.index')->with('success', 'Pemesanan berhasil dihapus.');
    }


    // ===============================================
    //           PUBLIC USER FLOW (P2.3 - P2.6)
    // ===============================================

    // /**
    //  * P2.3: Menampilkan daftar film yang sedang tayang (Home Publik).
    //  */
    // public function index()
    // {
    //     $films = Film::whereHas('jadwalTayangs', function ($query) {
    //         $query->where('tanggal_tayang', '>=', now()->toDateString());
    //     })->get();
    //     return view('publik.index', compact('films'));
    // }

    // ... (Fungsi publik lainnya yang sudah kita bahas sebelumnya)
    
    // public function showJadwal($film_id) { ... }
    // public function showKursi($jadwal_tayang_id) { ... }
    // public function store(Request $request) { ... }
}