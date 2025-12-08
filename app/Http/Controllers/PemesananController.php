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

    // Pastikan jadwal_seats sudah ter-generate; kalau belum, generate on-the-fly dari seats studio
    $jadwalSeats = \App\Models\JadwalSeat::where('jadwal_tayang_id', $jadwalId)->get();

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
        \DB::table('jadwal_seats')->insert($insert);
        $jadwalSeats = \App\Models\JadwalSeat::where('jadwal_tayang_id', $jadwalId)->get();
    }

    // kursi terisi = semua jadwal_seats dengan status 'booked' atau 'locked' (locked mungkin expired)
    $kursiTerisi = $jadwalSeats->filter(function($js) {
        return in_array($js->status, ['booked','locked']);
    })->pluck('nomor_kursi')->toArray();

    // Pass jadwalSeats too sehingga view bisa menampilkan status per seat
    return view('user.pemesanan.seat_selection', compact('jadwal', 'kursiTerisi', 'jadwalSeats'));
}

    
    /**
     * USER: Memproses pemesanan tiket dari pemilihan kursi (Step 2).
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPemesanan(Request $request)
{
    $request->validate([
        'jadwal_id' => 'required|exists:jadwal_tayangs,id',
        'kode_kursi' => 'required|string',
    ]);

    $userId = Auth::id();
    if (!$userId) {
        return redirect()->route('login')->with('error', 'Anda harus login untuk melanjutkan pemesanan.');
    }

    $jadwalId = $request->input('jadwal_id');
    $selectedSeats = array_filter(array_map('trim', explode(',', $request->input('kode_kursi'))));
    if (empty($selectedSeats)) {
        return redirect()->back()->with('error', 'Anda harus memilih minimal satu kursi.')->withInput();
    }

    $jadwal = JadwalTayang::findOrFail($jadwalId);
    $jumlahTiket = count($selectedSeats);
    $hargaPerTiket = $jadwal->harga;
    $totalHarga = $jumlahTiket * $hargaPerTiket;

    DB::beginTransaction();
    try {
        // Cek ketersediaan di jadwal_seats
        $conflicts = \App\Models\JadwalSeat::where('jadwal_tayang_id', $jadwalId)
            ->whereIn('nomor_kursi', $selectedSeats)
            ->whereIn('status', ['booked']) // treat 'booked' as unavailable
            ->pluck('nomor_kursi')->toArray();

        if (!empty($conflicts)) {
            DB::rollBack();
            $seatsList = implode(', ', $conflicts);
            return redirect()->back()->with('error', "Kursi berikut sudah terisi: {$seatsList}. Silakan pilih kursi lain.")->withInput();
        }

        // Optional: lock seats briefly to avoid race condition (optimistic)
        $now = now();
        \App\Models\JadwalSeat::where('jadwal_tayang_id', $jadwalId)
            ->whereIn('nomor_kursi', $selectedSeats)
            ->update(['status' => 'locked', 'locked_until' => $now->addMinutes(5)]);

        // Create Pemesanan
        $pemesanan = Pemesanan::create([
            'user_id' => $userId,
            'jadwal_id' => $jadwalId,
            'kode_pemesanan' => 'TKT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
            'jumlah_tiket' => $jumlahTiket,
            'total_harga' => $totalHarga,
            'status' => 'pending',
            'waktu_pemesanan' => now(),
        ]);

        // Create DetailPemesanan
        $details = [];
        foreach ($selectedSeats as $kursi) {
            $details[] = [
                'pemesanan_id' => $pemesanan->id,
                'jadwal_id' => $jadwalId,
                'nomor_kursi' => $kursi,
                'harga' => $hargaPerTiket,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DetailPemesanan::insert($details);

        // Mark jadwal_seats as booked
        \App\Models\JadwalSeat::where('jadwal_tayang_id', $jadwalId)
            ->whereIn('nomor_kursi', $selectedSeats)
            ->update(['status' => 'booked', 'locked_until' => null, 'updated_at' => now()]);

        DB::commit();

        return redirect()->route('user.pemesanan.show', ['kode_pemesanan' => $pemesanan->kode_pemesanan])
                         ->with('success', 'Pemesanan berhasil dibuat. Mohon selesaikan pembayaran.');
    } catch (Exception $e) {
        DB::rollBack();
        \Log::error('Gagal memproses pemesanan: ' . $e->getMessage());

        // Lepaskan locks jika ada
        \App\Models\JadwalSeat::where('jadwal_tayang_id', $jadwalId)
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