<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\JadwalTayang; // Pastikan model JadwalTayang sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class PemesananController extends Controller
{
    /**
     * ADMIN: Tampilkan daftar semua Pemesanan.
     * Menggunakan pagination.
     */
    public function index()
    {
        // Ambil pemesanan terbaru dengan relasi yang dibutuhkan
        $pemesanan = Pemesanan::with(['user', 'jadwal.film', 'jadwal.studio'])
            ->latest()
            ->paginate(15); 

        // Mengarah ke resources/views/admin/pemesanan/index.blade.php
        return view('admin.pemesanan.index', compact('pemesanan'));
    }

    /**
     * ADMIN: Tampilkan detail Pemesanan tertentu.
     */
    public function show(Pemesanan $pemesanan)
    {
        // Muat relasi detail (kursi) dan informasi terkait
        $pemesanan->load(['detailPemesanan', 'user', 'jadwal.film', 'jadwal.studio']);
        
        // Mengarah ke resources/views/admin/pemesanan/show.blade.php
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    /**
     * USER: Proses penyimpanan (transaksi) Pemesanan baru.
     * Ini adalah logika inti pembelian tiket.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_tayang,id',
            'nomor_kursi' => 'required|array|min:1',
            'nomor_kursi.*' => 'required|string|max:5', 
        ]);

        $userId = Auth::id(); 
        
        if (!$userId) {
            return response()->json(['error' => 'Anda harus login untuk membuat pemesanan.'], 401);
        }

        $jadwalId = $request->input('jadwal_id');
        $nomorKursi = $request->input('nomor_kursi');
        $jumlahTiket = count($nomorKursi);
        
        // Ambil Jadwal Tayang untuk mendapatkan harga tiket
        $jadwal = JadwalTayang::findOrFail($jadwalId);
        $hargaPerTiket = $jadwal->harga; // Asumsi kolom harga ada di tabel jadwal_tayang
        $totalHarga = $jumlahTiket * $hargaPerTiket;
        
        DB::beginTransaction();
        try {
            // Cek Ketersediaan Kursi (Penting!)
            // Ambil semua kursi yang sudah dipesan untuk jadwal ini (status paid atau pending)
            $occupiedSeats = DetailPemesanan::whereHas('pemesanan', function($query) use ($jadwalId) {
                $query->where('jadwal_id', $jadwalId)
                      ->whereIn('status', ['paid', 'pending']); // Kursi yang sedang dipesan atau sudah lunas
            })->whereIn('nomor_kursi', $nomorKursi)
              ->pluck('nomor_kursi')->toArray();

            if (!empty($occupiedSeats)) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Kursi berikut sudah dipesan atau dalam proses pembayaran: ' . implode(', ', $occupiedSeats)
                ], 409); // Conflict
            }

            // 2. Buat Pemesanan (HEADER/RINGKASAN)
            $pemesanan = Pemesanan::create([
                'user_id' => $userId,
                'jadwal_id' => $jadwalId,
                // Pastikan kolom harga di tabel JadwalTayang sudah ada
                'kode_pemesanan' => 'TKT-' . date('Ymd') . '-' . time() . '-' . rand(100, 999), 
                'jumlah_tiket' => $jumlahTiket,
                'total_harga' => $totalHarga,
                'status' => 'pending', 
                'waktu_pemesanan' => now(),
            ]);

            // 3. Buat Detail Pemesanan (ITEM/KURSI)
            $details = [];
            foreach ($nomorKursi as $kursi) {
                $details[] = [
                    'pemesanan_id' => $pemesanan->id,
                    'nomor_kursi' => $kursi,
                    'created_at' => now(), 
                    'updated_at' => now(), 
                ];
            }
            DetailPemesanan::insert($details);

            // 4. Commit Transaksi
            DB::commit();

            return response()->json([
                'success' => 'Pemesanan berhasil dibuat. Menunggu pembayaran.',
                'pemesanan_id' => $pemesanan->id,
                'kode_pemesanan' => $pemesanan->kode_pemesanan,
                'total_harga' => $pemesanan->total_harga
            ], 201); // Created

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Gagal membuat pemesanan: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem saat memproses pemesanan.'], 500);
        }
    }
    
    /**
     * ADMIN: Hapus pemesanan
     */
    public function destroy(Pemesanan $pemesanan)
    {
        try {
            $pemesanan->delete();
            return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('pemesanan.index')->with('error', 'Gagal menghapus pemesanan. Terjadi kesalahan.');
        }
    }
}