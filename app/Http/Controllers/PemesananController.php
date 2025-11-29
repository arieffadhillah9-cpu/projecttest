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
    // ====================================================================
    // ADMIN FUNCTIONS (RETURN VIEW)
    // ====================================================================

    /**
     * ADMIN: Tampilkan daftar semua Pemesanan (Rute: admin.pemesanan.index).
     */
    public function index()
    {
        $pemesanan = Pemesanan::with(['user', 'jadwal.film', 'jadwal.studio'])
            ->latest()
            ->paginate(15); 

        return view('admin.pemesanan.index', compact('pemesanan'));
    }

    /**
     * ADMIN: Tampilkan detail Pemesanan tertentu (Rute: admin.pemesanan.show).
     */
    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load(['detailPemesanan', 'user', 'jadwal.film', 'jadwal.studio']);
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    /**
     * ADMIN: Tampilkan form untuk mengedit status pemesanan.
     */
    public function edit(Pemesanan $pemesanan)
    {
        $statuses = ['pending', 'paid', 'expired', 'canceled'];
        return view('admin.pemesanan.edit', compact('pemesanan', 'statuses'));
    }

    /**
     * ADMIN: Perbarui status pemesanan di database (Rute: admin.pemesanan.update.status).
     */
    public function updateStatus(Request $request, Pemesanan $pemesanan)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,expired,canceled', 
        ]);

        $pemesanan->update($validated);

        return redirect()->route('admin.pemesanan.index')
                         ->with('success', 'Status pemesanan berhasil diperbarui.');
    }

    /**
     * ADMIN: Hapus pemesanan (Rute: admin.pemesanan.destroy).
     */
    public function destroy(Pemesanan $pemesanan)
    {
        try {
            $pemesanan->delete();
            
            return redirect()->route('admin.pemesanan.index')->with('success', 'Pemesanan berhasil dihapus.'); 
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus pemesanan: ' . $e->getMessage());
            return redirect()->route('admin.pemesanan.index')->with('error', 'Gagal menghapus pemesanan. Terjadi kesalahan.');
        }
    }

    // ====================================================================
    // USER/API FUNCTIONS (RETURN JSON)
    // ====================================================================

    /**
     * API: Mengambil daftar nomor kursi yang sudah terisi (occupied) untuk jadwal tertentu.
     * Kursi dianggap terisi jika status pemesanannya 'paid' atau 'pending'.
     * * Rute yang disarankan: GET /api/pemesanan/occupied-seats?jadwal_id={id}
     * * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOccupiedSeats(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_tayangs,id',
        ]);

        $jadwalId = $request->input('jadwal_id');

        try {
            // Kita menggunakan query yang hampir sama dengan yang ada di method store()
            // untuk memastikan konsistensi dalam penentuan status 'occupied'.
            $occupiedSeats = DetailPemesanan::whereHas('pemesanan', function($query) use ($jadwalId) {
                // Filter hanya pemesanan yang berkaitan dengan jadwal ini
                $query->where('jadwal_id', $jadwalId)
                      // Filter hanya pemesanan yang statusnya PAID (sudah dibayar) atau PENDING (masih dalam masa tunggu)
                      ->whereIn('status', ['paid', 'pending']); 
            })
            ->pluck('nomor_kursi') // Ambil hanya kolom nomor_kursi
            ->unique() // Pastikan tidak ada duplikasi (meskipun seharusnya tidak terjadi)
            ->values() // Reset key array
            ->toArray();

            return response()->json([
                'jadwal_id' => $jadwalId,
                'occupied_seats' => $occupiedSeats,
                'count' => count($occupiedSeats),
            ], 200);

        } catch (Exception $e) {
            \Log::error('Gagal mengambil daftar kursi terisi: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem saat mengambil data kursi.'], 500);
        }
    }
    
    /**
     * USER: Tampilkan daftar Pemesanan milik user yang sedang login (Rute: pemesanan.index).
     * @return \Illuminate\Http\JsonResponse
     */
    public function userIndex()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized. Mohon login terlebih dahulu.'], 401);
        }

        $pemesanan = Pemesanan::where('user_id', $userId)
            ->with(['jadwal.film', 'jadwal.studio'])
            ->latest()
            ->paginate(10);

        return response()->json($pemesanan, 200);
    }

    /**
     * USER: Tampilkan detail Pemesanan milik user yang sedang login (Rute: pemesanan.show).
     * @param Pemesanan $pemesanan
     * @return \Illuminate\Http\JsonResponse
     */
    public function userShow(Pemesanan $pemesanan)
    {
        // Cek otorisasi: pastikan pemesanan ini milik user yang sedang login
        if ($pemesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak. Pemesanan tidak ditemukan.'], 403);
        }

        $pemesanan->load(['detailPemesanan', 'user', 'jadwal.film', 'jadwal.studio']);

        return response()->json($pemesanan, 200);
    }

    /**
     * USER: Proses penyimpanan (transaksi) Pemesanan baru (Rute: pemesanan.store).
     * Fungsi ini dipanggil dari API/Frontend User.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_tayangs,id',
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
        $hargaPerTiket = $jadwal->harga; // Asumsi kolom harga ada di tabel jadwal_tayangs
        $totalHarga = $jumlahTiket * $hargaPerTiket;
        
        DB::beginTransaction();
        try {
            // Cek Ketersediaan Kursi (Penting!)
            // Kursi dianggap terisi jika statusnya 'paid' atau 'pending' (masih dalam masa tunggu pembayaran)
            $occupiedSeats = DetailPemesanan::whereHas('pemesanan', function($query) use ($jadwalId) {
                $query->where('jadwal_id', $jadwalId)
                      ->whereIn('status', ['paid', 'pending']); 
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
                // Generate kode pemesanan yang lebih unik dan mudah dilacak
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
                    'harga_satuan' => $hargaPerTiket,
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
     * USER: Simulasi proses konfirmasi pembayaran untuk Pemesanan.
     * Metode ini akan mengubah status dari 'pending' menjadi 'paid'.
     * @param Pemesanan $pemesanan
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Pemesanan $pemesanan)
    {
        // 1. Cek Otorisasi
        if ($pemesanan->user_id !== Auth::id()) {
            return response()->json(['error' => 'Akses ditolak. Pemesanan tidak ditemukan.'], 403);
        }

        // 2. Cek Status saat ini
        if ($pemesanan->status !== 'pending') {
            return response()->json(['error' => 'Pembayaran hanya bisa dikonfirmasi pada pemesanan berstatus pending/menunggu.'], 400);
        }

        DB::beginTransaction();
        try {
            // 3. Update Status
            $pemesanan->update([
                'status' => 'paid',
                'waktu_pembayaran' => now(),
            ]);
            
            // 4. Commit
            DB::commit();

            return response()->json([
                'success' => 'Pembayaran berhasil dikonfirmasi. Tiket Anda sudah aktif.',
                'pemesanan' => $pemesanan,
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Gagal memproses pembayaran: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem saat memproses pembayaran.'], 500);
        }
    }
}