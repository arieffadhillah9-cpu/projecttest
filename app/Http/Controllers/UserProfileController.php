<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;
use App\Models\Film;
use App\Models\DetailPemesanan;
use App\Models\JadwalSeat;

class UserProfileController extends Controller
{
    // Fungsi untuk membuat objek data mock Pemesanan, Jadwal, Film, dll.
    private function createMockPemesanan(int $id, string $kode, string $status)
    {
        // 1. Mock Film
        $film = (object)[
            'judul' => 'Inception: The Dream Heist',
        ];

        // 2. Mock Studio
        $studio = (object)[
            'nama' => 'A' . $id % 5, // Studio A1, A2, A3, dst.
        ];

        // 3. Mock Jadwal
        $jadwal = (object)[
            'waktu_tayang' => now()->addDays(-$id)->format('Y-m-d H:i:s'),
            'film' => $film,
            'studio' => $studio,
        ];
        
        // 4. Mock Detail Pemesanan (Kursi)
        $detailPemesanan = new Collection();
        $detailPemesanan->push((object)['nomor_kursi' => 'A' . (10 + $id)]);
        $detailPemesanan->push((object)['nomor_kursi' => 'B' . (11 + $id)]);
        $detailPemesanan->push((object)['nomor_kursi' => 'C' . (12 + $id)]);

        // 5. Mock Pemesanan Utama
        return (object)[
            'kode_pemesanan' => $kode,
            'status' => $status,
            'jumlah_tiket' => 3,
            'total_harga' => 100000 + ($id * 5000), // Harga unik
            'jadwal' => $jadwal,
            'detailPemesanan' => $detailPemesanan,
        ];
    }

    /**
     * Tampilkan riwayat semua pemesanan (menggunakan data mock sementara).
     */
    public function history()
    {
        // =========================================================
        // START MOCK DATA (Simulasi Data Database)
        // =========================================================
        $mockData = [
            $this->createMockPemesanan(1, 'SEAT-1123', 'paid'),
            $this->createMockPemesanan(2, 'SEAT-2897', 'pending'),
            $this->createMockPemesanan(3, 'SEAT-3456', 'expired'),
            $this->createMockPemesanan(4, 'SEAT-4789', 'paid'),
            $this->createMockPemesanan(5, 'SEAT-5601', 'canceled'),
            $this->createMockPemesanan(6, 'SEAT-6123', 'paid'),
        ];
        
        $perPage = 10;
        $page = request('page', 1);
        $offset = ($page * $perPage) - $perPage;

        // Simulasi Pagination (untuk menguji links() di Blade)
        $pemesanans = new LengthAwarePaginator(
            array_slice($mockData, $offset, $perPage, true),
            count($mockData),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
        // =========================================================
        // END MOCK DATA
        // =========================================================

        // Mengarah ke resources/views/user/history.blade.php
        return view('user.history', compact('pemesanans'));
    }

    /**
     * Tampilkan detail pemesanan spesifik (menggunakan data mock sementara).
     */
    public function showPemesanan($kode_pemesanan)
    {
        // 1. Ambil data pemesanan beserta relasi yang diperlukan (Film, Studio, Kursi)
        $pemesanan = Pemesanan::with([
            'jadwal.film', 
            'jadwal.studio',
            'detailPemesanan'
            
        ])
        // 2. Cari berdasarkan kode_pemesanan
        ->where('kode_pemesanan', $kode_pemesanan)
        // 3. Batasi hanya untuk user yang sedang login (keamanan)
        ->where('user_id', auth()->id())
        ->first();

        // 4. Handle jika pemesanan tidak ditemukan
        if (!$pemesanan) {
            return redirect()->route('user.history')->with('error', 'Detail pemesanan tidak ditemukan.');
        }

        // 5. Hitung Batas Waktu Pembayaran (Contoh: 30 menit dari waktu pembuatan)
        $waktuKadaluwarsa = Carbon::parse($pemesanan->created_at)->addMinutes(30);
        $sekarang = Carbon::now();
        
        // 6. Tentukan status pembayaran (apakah masih berlaku atau sudah kadaluwarsa)
        $isExpired = $sekarang->greaterThan($waktuKadaluwarsa) && $pemesanan->status === 'menunggu_pembayaran';


        // 7. Redirect jika pemesanan sudah kadaluwarsa
       if ($isExpired) {
    if ($pemesanan->status === 'menunggu_pembayaran') {
        // --- LOG 1: PEMESANAN DIBAWALKAN ---
        Log::info("Pemesanan [{$kode_pemesanan}] kedaluwarsa. Mengubah status dan melepaskan kursi."); 
        
        $pemesanan->status = 'expired';
        $pemesanan->save();
        
        // Cek apakah detail kursi ada
        $nomorKursiDibatalkan = $pemesanan->detailPemesanan->pluck('nomor_kursi')->toArray();
        
        // --- LOG 2: KURSI YANG DITEMUKAN ---
        Log::info("Kursi yang ditemukan untuk pelepasan: " . implode(', ', $nomorKursiDibatalkan));

        if (!empty($nomorKursiDibatalkan)) {
            // Logika Update status di tabel jadwal_seats
            $updatedRows = JadwalSeat::where('jadwal_tayang_id', $pemesanan->jadwal_id)
                ->whereIn('nomor_kursi', $nomorKursiDibatalkan)
                ->update(['status' => 'available']);
            
            // --- LOG 3: JUMLAH BARIS YANG DIUPDATE ---
            Log::info("Jumlah baris JadwalSeat yang berhasil diupdate: {$updatedRows}");

            // Hapus detail pemesanan
            $pemesanan->detailPemesanan()->delete();
        } else {
             Log::warning("Detail kursi tidak ditemukan untuk kode pemesanan [{$kode_pemesanan}]. Kursi tidak dilepas.");
        }
    }
    return redirect()->route('user.history')->with('error', 'Waktu pembayaran telah habis.');
}
        $films = \App\Models\Film::all(); // Ganti dengan query yang lebih spesifik jika perlu
        // 8. Kirim data ke view
        return view('user.pemesanan.show_pemesanan', compact('pemesanan', 'waktuKadaluwarsa','films'));
    }
}