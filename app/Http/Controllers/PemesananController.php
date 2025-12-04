<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\JadwalTayang; 
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
    
    // --- START: FUNGSI BARU UNTUK USER: PEMILIHAN KURSI ---

    /**
     * USER: Menampilkan formulir pemilihan kursi untuk jadwal tertentu.
     * Digunakan sebagai step 1 sebelum POST ke processPemesanan.
     * @param int $jadwalId
     * @return \Illuminate\View\View
     */
    public function selectSeat($jadwalId)
    {
        $jadwal = JadwalTayang::with(['film', 'studio'])->findOrFail($jadwalId);
        $studio = $jadwal->studio;

        // Mendapatkan daftar kursi yang sudah terisi untuk jadwal ini
        // Status 'paid' dan 'pending' dianggap sudah terisi/dipesan
        $kursiTerisi = DetailPemesanan::whereHas('pemesanan', function ($query) use ($jadwalId) {
            // Pengecekan 'jadwal_id' dilakukan pada tabel Pemesanan (induk)
            $query->where('jadwal_id', $jadwalId);
            $query->whereIn('status', ['paid', 'pending']); 
        })->pluck('nomor_kursi')->toArray();

        // Mengirim data ke view seat_selection
        // Asumsi: Studio memiliki kolom jumlah_baris dan jumlah_kolom
        $rowCount = $studio->jumlah_baris_kursi ?? 5; // Default jika kolom tidak ada
        $columnCount = $studio->jumlah_kolom_kursi ?? 10; // Default jika kolom tidak ada
        
        return view('user.pemesanan.seat_selection', compact('jadwal', 'studio', 'kursiTerisi', 'rowCount', 'columnCount'));
    }

    
    /**
     * USER: Memproses pemesanan tiket dari pemilihan kursi (Step 2).
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPemesanan(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_tayangs,id',
            // Nama input diubah menjadi 'kode_kursi' agar sesuai dengan revisi View
            'kode_kursi' => 'required|string', 
        ]);
        
        $userId = Auth::id(); 
        if (!$userId) {
            // Seharusnya middleware('auth') sudah menangani ini, tapi baik untuk pengecekan
            return redirect()->route('login')->with('error', 'Anda harus login untuk melanjutkan pemesanan.');
        }

        $jadwalId = $request->input('jadwal_id');
        // Konversi string kursi (A1,B3,C5) menjadi array
        $selectedSeats = array_filter(explode(',', $request->input('kode_kursi'))); 
        
        if (empty($selectedSeats)) {
            return redirect()->back()->with('error', 'Anda harus memilih minimal satu kursi.')->withInput();
        }

        $jadwal = JadwalTayang::findOrFail($jadwalId);
        $jumlahTiket = count($selectedSeats);
        
        // Menggunakan $jadwal->harga
        $hargaPerTiket = $jadwal->harga; 
        
        $totalHarga = $jumlahTiket * $hargaPerTiket;
        
        DB::beginTransaction();
        try {
            // Cek Ketersediaan Kursi (Penting untuk mencegah double booking)
            $occupiedSeats = DetailPemesanan::where('jadwal_id', $jadwalId)
                ->whereIn('nomor_kursi', $selectedSeats)
                ->whereHas('pemesanan', function($query) {
                    $query->whereIn('status', ['paid', 'pending']); 
                })
                ->pluck('nomor_kursi')->toArray();

            if (!empty($occupiedSeats)) {
                DB::rollBack();
                $seatsList = implode(', ', $occupiedSeats);
                return redirect()->back()->with('error', "Kursi berikut sudah terisi: {$seatsList}. Silakan pilih kursi lain.")->withInput();
            }

            // 2. Buat Pemesanan (HEADER/RINGKASAN)
            $pemesanan = Pemesanan::create([
                'user_id' => $userId,
                'jadwal_id' => $jadwalId,
                // Menggunakan random string yang lebih unik
                'kode_pemesanan' => 'TKT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)), 
                'jumlah_tiket' => $jumlahTiket,
                'total_harga' => $totalHarga,
                'status' => 'pending', 
                'waktu_pemesanan' => now(),
            ]);

            // 3. Buat Detail Pemesanan (ITEM/KURSI)
            $details = [];
            foreach ($selectedSeats as $kursi) {
                $details[] = [
                    'pemesanan_id' => $pemesanan->id,
                    // Tambahkan jadwal_id di detail jika diperlukan untuk query cepat
                    'jadwal_id' => $jadwalId, 
                    'nomor_kursi' => $kursi,
                    'harga' => $hargaPerTiket, // Simpan harga saat ini di detail
                    'created_at' => now(), 
                    'updated_at' => now(), 
                ];
            }
            DetailPemesanan::insert($details);

            // 4. Commit Transaksi
            DB::commit();

            // Arahkan ke halaman detail pemesanan user untuk proses pembayaran
            return redirect()->route('user.pemesanan.show', ['kode_pemesanan' => $pemesanan->kode_pemesanan])
                             ->with('success', 'Pemesanan berhasil dibuat. Mohon selesaikan pembayaran.');

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Gagal memproses pemesanan: ' . $e->getMessage()); 
            
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses pemesanan. Silakan coba lagi.')->withInput();
        }
    }

    // --- END: FUNGSI BARU UNTUK USER: PEMILIHAN KURSI ---
    
    // Metode-metode lain...

    /**
     * ADMIN: Hapus pemesanan
     */
    public function destroy(Pemesanan $pemesanan)
    {
        try {
            $pemesanan->delete();
            return redirect()->route('admin.pemesanan.index')->with('success', 'Pemesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pemesanan.index')->with('error', 'Gagal menghapus pemesanan. Terjadi kesalahan.');
        }
    }
    
    /**
     * ADMIN: Update status pemesanan (e.g., dari pending ke paid, atau cancel).
     * @param Request $request
     * @param Pemesanan $pemesanan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Pemesanan $pemesanan)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,expired,canceled',
        ]);

        try {
            $statusBaru = $request->input('status');
            $dataUpdate = ['status' => $statusBaru];

            // Jika status menjadi paid, catat waktu pembayarannya
            if ($statusBaru === 'paid') {
                $dataUpdate['waktu_pembayaran'] = now();
            }

            $pemesanan->update($dataUpdate);

            return redirect()->route('admin.pemesanan.show', $pemesanan)->with('success', "Status pemesanan #{$pemesanan->kode_pemesanan} berhasil diperbarui menjadi {$statusBaru}.");
        } catch (\Exception $e) {
            \Log::error('Gagal update status pemesanan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status pemesanan. Terjadi kesalahan sistem.');
        }
    }
}