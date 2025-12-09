<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\JadwalTayang; 
use App\Models\JadwalSeat; // Model ini digunakan di dalam selectSeat dan processPemesanan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Pastikan Log di-import untuk error handling
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
     * @param int $jadwalId
     * @return \Illuminate\View\View
     */
    public function selectSeat($jadwalId)
    {
        $jadwal = JadwalTayang::with(['film', 'studio'])->findOrFail($jadwalId);

        // Ambil objek film dari relasi jadwal
        $film = $jadwal->film;
        
        // Definisikan variabel dummy yang mungkin dibutuhkan di layout (seperti pada kode Anda)
        $availableDates = [];
        $allSchedules = []; 

        // Pastikan jadwal_seats sudah ter-generate; kalau belum, generate on-the-fly dari seats studio
        $jadwalSeats = JadwalSeat::where('jadwal_tayang_id', $jadwalId)->get();

        if ($jadwalSeats->isEmpty()) {
            // trigger manual generation (mirip booted) - fallback
            $seats = \App\Models\Seat::where('studio_id', $jadwal->studio_id)->get();
            $insert = [];
            if ($seats->isEmpty()) {
                $kapasitas = $jadwal->studio->kapasitas ?? 50;
                for ($i = 1; $i <= $kapasitas; $i++) {
                    $insert[] = [
                        'jadwal_tayang_id' => $jadwal->id,
                        'seat_id' => null,
                        'nomor_kursi' => 'S' . $i,
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            } else {
                foreach ($seats as $s) {
                    $insert[] = [
                        'jadwal_tayang_id' => $jadwal->id,
                        'seat_id' => $s->id,
                        'nomor_kursi' => $s->nomor_kursi,
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            DB::table('jadwal_seats')->insert($insert);
            $jadwalSeats = JadwalSeat::where('jadwal_tayang_id', $jadwalId)->get();
        }

        // kursi terisi = semua jadwal_seats dengan status 'booked' atau 'locked' 
        // Menggunakan snake_case ($kursi_terisi) agar sesuai dengan view Anda.
        $kursi_terisi = $jadwalSeats->filter(function($js) {
            return in_array($js->status, ['booked','locked']);
        })->pluck('nomor_kursi')->toArray();

        // PENTING: Menggunakan 'kursi_terisi' dalam compact()
        return view('user.pemesanan.seat_selection', compact('jadwal', 'kursi_terisi', 'jadwalSeats', 'film', 'availableDates', 'allSchedules'));
    }

    /**
     * USER: Memproses pemesanan tiket dari pemilihan kursi (Step 2).
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */ public function processPemesanan(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_tayangs,id',
            'kode_kursi' => 'required|string',
        ]);

        // 2. Autentikasi Pengguna
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melanjutkan pemesanan.');
        }

        // 3. Pembersihan dan Pengecekan Kursi
        $selectedSeats = array_filter(array_map('trim', explode(',', $request->input('kode_kursi'))));
        
        if (empty($selectedSeats)) {
            return redirect()->back()->with('error', 'Anda harus memilih minimal satu kursi.')->withInput();
        }

        // 4. Ambil Data Jadwal & Hitung Harga
        $jadwal = JadwalTayang::findOrFail($request->input('jadwal_id'));
        $jumlahTiket = count($selectedSeats);
        $hargaPerTiket = $jadwal->harga;
        $totalHarga = $jumlahTiket * $hargaPerTiket;

        // ===============================================
        // 5. TRANSAKSI DATABASE (PENTING UNTUK KONSISTENSI)
        // ===============================================
        DB::beginTransaction();
        try {
            // --- PROTEKSI RACE CONDITION (LOCKING) ---
            $conflicts = JadwalSeat::where('jadwal_tayang_id', $jadwal->id)
                ->whereIn('nomor_kursi', $selectedSeats)
                ->whereIn('status', ['booked', 'locked']) // Periksa status 'booked' DAN 'locked'
                ->lockForUpdate() // Kunci baris-baris ini selama transaksi
                ->pluck('nomor_kursi')
                ->toArray();

            if (!empty($conflicts)) {
                DB::rollBack();
                $seatsList = implode(', ', $conflicts);
                return redirect()->back()->with('error', "Kursi berikut sudah terisi atau sedang diproses oleh pengguna lain: {$seatsList}. Silakan pilih kursi lain.")->withInput();
            }

            // --- OPTIONAL: UPDATE STATUS KE 'LOCKED' ---
            $now = now();
            JadwalSeat::where('jadwal_tayang_id', $jadwal->id)
                ->whereIn('nomor_kursi', $selectedSeats)
                ->update(['status' => 'locked', 'locked_until' => $now->copy()->addMinutes(5)]);


            // --- CREATE PEMESANAN ---
            $pemesanan = Pemesanan::create([
                'user_id' => $userId,
                'jadwal_id' => $jadwal->id,
                'kode_pemesanan' => 'TKT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)), 
                'jumlah_tiket' => $jumlahTiket,
                'total_harga' => $totalHarga,
                'status' => 'pending', // Status awal: menunggu pembayaran
                'waktu_pemesanan' => $now,
            ]);

            // --- CREATE DETAIL PEMESANAN ---
            $details = [];
            foreach ($selectedSeats as $kursi) {
                $details[] = [
                    'pemesanan_id' => $pemesanan->id,
                    'jadwal_id' => $jadwal->id, 
                    'nomor_kursi' => $kursi,
                    'harga' => $hargaPerTiket,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            DetailPemesanan::insert($details);

            // --- UPDATE STATUS KURSI JADI 'BOOKED' (Selesai diproses) ---
            JadwalSeat::where('jadwal_tayang_id', $jadwal->id)
                ->whereIn('nomor_kursi', $selectedSeats)
                ->update(['status' => 'booked', 'locked_until' => null, 'updated_at' => now()]);


            // Commit transaksi jika semua berhasil
            DB::commit();

            // 6. Redirect Sukses
            return redirect()
                ->route('user.pemesanan.show', ['kode_pemesanan' => $pemesanan->kode_pemesanan])
                ->with('success', 'Pemesanan berhasil dibuat. Mohon selesaikan pembayaran dalam waktu 5 menit.');

        } catch (Exception $e) {
            DB::rollBack();
            // PENTING: Pastikan Anda telah mengimpor Facade Log di atas.
            Log::error('Gagal memproses pemesanan (Rollback): ' . $e->getMessage() . ' | User ID: ' . $userId);

            // --- CLEANUP LOCKING (Jika terjadi kegagalan sistem) ---
            JadwalSeat::where('jadwal_tayang_id', $jadwal->id)
                ->whereIn('nomor_kursi', $selectedSeats)
                ->where('status', 'locked')
                ->update(['status' => 'available', 'locked_until' => null]);
            
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
     * ADMIN: Update status pemesanan.
     */
    public function updateStatus(Request $request, Pemesanan $pemesanan)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,expired,canceled',
        ]);

        try {
            $statusBaru = $request->input('status');
            $dataUpdate = ['status' => $statusBaru];

            if ($statusBaru === 'paid') {
                $dataUpdate['waktu_pembayaran'] = now();
            }

            $pemesanan->update($dataUpdate);

            return redirect()->route('admin.pemesanan.show', $pemesanan)->with('success', "Status pemesanan #{$pemesanan->kode_pemesanan} berhasil diperbarui menjadi {$statusBaru}.");
        } catch (\Exception $e) {
            Log::error('Gagal update status pemesanan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status pemesanan. Terjadi kesalahan sistem.');
        }
    }
    
}