<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function showPemesanan(string $kode_pemesanan)
    {
        // =========================================================
        // START MOCK DATA (Simulasi Data Database)
        // =========================================================
        // Kita hanya mengembalikan satu data yang lengkap untuk diuji
        $pemesanan = $this->createMockPemesanan(1, $kode_pemesanan, 'paid');
        // =========================================================
        // END MOCK DATA
        // =========================================================

        // Simulasi logika not found/unauthorized (opsional)
        if (!in_array($kode_pemesanan, ['SEAT-1123', 'MOCK_TEST'])) {
             // Jika kode_pemesanan tidak cocok dengan mock, kita anggap 404
             // Hapus baris ini setelah pengujian selesai
             // abort(404, 'Pemesanan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Mengarah ke resources/views/user/pemesanan/show.blade.php
        return view('user.pemesanan.show', compact('pemesanan'));
    }
}