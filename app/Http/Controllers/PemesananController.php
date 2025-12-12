<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\JadwalTayang; 
use App\Models\JadwalSeat; // Model ini digunakan di dalam selectSeat dan processPemesanan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Pastikan Log di-import untuk error handling
use Exception;
use Midtrans\Config;
use Midtrans\Snap;

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
    
    // --- PERBAIKAN: Tambahkan variabel $films yang dibutuhkan oleh layout ---
    // Asumsi: Layout Anda membutuhkan daftar semua film. Sesuaikan query ini
    // dengan kebutuhan riil data film yang ingin ditampilkan di layout (misalnya di sidebar).
    
    // Pastikan Anda mengimpor Model Film di awal file Controller jika belum: use App\Models\Film;
    $films = \App\Models\Film::all(); 
    
    // ----------------------------------------------------------------------
    
    // Definisikan variabel dummy yang mungkin dibutuhkan di layout (seperti pada kode Anda)
    $availableDates = [];
    $allSchedules = []; 

    // Pastikan jadwal_seats sudah ter-generate; kalau belum, generate on-the-fly dari seats studio
    $jadwalSeats = JadwalSeat::where('jadwal_tayang_id', $jadwalId)->get();

    if ($jadwalSeats->isEmpty()) {
        // ... (Logika fallback generasi kursi, DIBIARKAN SAMA) ...
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

    // PENTING: Tambahkan 'films' ke dalam compact()
    return view('user.pemesanan.select_seat', compact('jadwal', 'kursi_terisi', 'jadwalSeats', 'film', 'availableDates', 'allSchedules', 'films'));
}

    /**
     * USER: Memproses pemesanan tiket dari pemilihan kursi (Step 2).
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */ 

   public function processPemesanan(Request $request)
{
    \Log::info('Proses Pemesanan Dimulai', $request->all());
    // A. Validasi (Ini yang paling sering menyebabkan reload tanpa error)
    $request->validate([
        'jadwal_id' => 'required|exists:jadwal_tayangs,id',
        'kursi_dipilih' => 'required|array|min:1',
        'kursi_dipilih.*' => 'required|string',
    ]);

    $jadwalId = $request->input('jadwal_id');
    $kursiDipilih = $request->input('kursi_dipilih');
    $userId = auth()->id();
    // -----------------------------------------------------------------
    // A.1. AMBIL HARGA DINAMIS DARI JADWAL TAYANG
    // -----------------------------------------------------------------
    $jadwal = JadwalTayang::find($jadwalId);

    if (!$jadwal) {
        return back()->with('error', 'Jadwal tayang tidak valid atau tidak ditemukan.');
    }
    
    // A.2. DEFINISIKAN HARGA DARI DATABASE
    $hargaPerKursi = $jadwal->harga; // <<< HARGA DIAMBIL DARI KOLOM 'harga' DI TABEL JADWAL_TAYANG
    \Log::info('Harga per Kursi Ditemukan: ' . $hargaPerKursi);
    // -----------------------------------------------------------------
    DB::beginTransaction();

    try {
        \Log::info('Start Kursi Locking Check'); // Log Step 1
        // B. Kunci Kursi
        // Pastikan kursi benar-benar available, jika tidak, rollback
        $seatsToLock = JadwalSeat::where('jadwal_tayang_id', $jadwalId)
            ->whereIn('nomor_kursi', $kursiDipilih)
            ->where('status', 'available')
            ->get();
        // *** LOG BARU UNTUK VERIFIKASI ***
    $totalKursiDipilih = count($kursiDipilih);
    $totalKursiDitemukan = $seatsToLock->count();
    \Log::info("Verifikasi Kursi: Dipilih={$totalKursiDipilih}, Ditemukan Available={$totalKursiDitemukan}");

        if ($seatsToLock->count() !== count($kursiDipilih)) {
            \Log::warning('Gagal mengunci kursi. Kursi tidak available.'); // <-- Log Jika Gagal
            DB::rollBack();
            return back()->with('error', 'Maaf, salah satu kursi yang Anda pilih baru saja terisi oleh pengguna lain.');
        }

        // Update status menjadi 'locked'
       JadwalSeat::whereIn('id', $seatsToLock->pluck('id'))
    ->update([
        'status' => 'locked',
        // BERIKAN NILAI TIMESTAMP YANG TIDAK NULL
        'locked_until' => Carbon::now()->addMinutes(15) 
    ]);

\Log::info('Kursi berhasil dikunci.');
        // C. Buat Record Pemesanan
        $pemesanan = Pemesanan::create([
            'user_id' => $userId,
            'jadwal_id' => $jadwalId,         // <-- Benar untuk tabel `pemesanan`
    'kode_pemesanan' => 'P-' . strtoupper(uniqid()),
    'jumlah_tiket' => count($kursiDipilih), // <-- Benar untuk tabel `pemesanan`
            'total_harga' => count($kursiDipilih) * $hargaPerKursi,
            'status' => 'menunggu_pembayaran',
        ]);
        $detailPemesananData = [];
    
    foreach ($kursiDipilih as $kursi) {
        $detailPemesananData[] = [
            'pemesanan_id' => $pemesanan->id, // Menggunakan ID Pemesanan yang baru dibuat
            'jadwal_id' => $jadwalId, 
            'nomor_kursi' => $kursi,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    // Simpan semua detail kursi sekaligus
    DetailPemesanan::insert($detailPemesananData); 
    // Pastikan Model DetailPemesanan di-import di awal file: use App\Models\DetailPemesanan;
    
    \Log::info('Detail Pemesanan berhasil dibuat.');
    // --------------------------------------------------------------------
    
    \Log::info('Pemesanan berhasil dibuat: ' . $pemesanan->kode_pemesanan); // Log Step 3
    
    // D. Commit Transaksi
    DB::commit();

    // E. Redirect ke Halaman Pembayaran (user.pemesanan.show)
    return redirect()->route('user.pemesanan.show', ['kode_pemesanan' => $pemesanan->kode_pemesanan])
        ->with('success', 'Pemesanan berhasil dibuat. Silakan selesaikan pembayaran.');
        \Log::info('Pemesanan berhasil dibuat: ' . $pemesanan->kode_pemesanan); // Log Step 3
     

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Pemesanan Gagal: ' . $e->getMessage()); 
        return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
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
    public function showPaymentPage($pemesananId) 
{
    // A. Ambil Data Pemesanan
    $pemesanan = Pemesanan::with(['jadwal.film', 'jadwal.studio'])
                            ->where('user_id', auth()->id()) // Amankan dari user lain
                            ->findOrFail($pemesananId);

    // B. Kirim data ke View Pembayaran
    return view('user.pemesanan.payment', compact('pemesanan'));
}
public function confirmPayment($kode_pemesanan)
    {
        // A. Ambil Pemesanan
        $pemesanan = Pemesanan::with('detailPemesanan')
            ->where('kode_pemesanan', $kode_pemesanan)
            ->where('user_id', auth()->id())
            ->where('status', 'menunggu_pembayaran')
            ->first();

        // Cek jika pemesanan tidak ditemukan, sudah dibayar, atau kadaluwarsa
        if (!$pemesanan) {
            return redirect()->route('user.history')->with('error', 'Pemesanan tidak ditemukan, sudah dibayar, atau telah kadaluwarsa.');
        }

        // Jalankan transaksi database
        DB::beginTransaction();
        try {
            // 1. Ambil nomor kursi yang akan di-update
            $nomorKursi = $pemesanan->detailPemesanan->pluck('nomor_kursi')->toArray();
            $jadwalId = $pemesanan->jadwal_id;

            // 2. Update status Pemesanan menjadi 'paid'
            $pemesanan->update([
                'status' => 'paid',
                'waktu_pembayaran' => now(), // Catat waktu pembayaran
            ]);
            \Log::info("Pemesanan [{$kode_pemesanan}] berhasil dibayar."); // Log untuk debugging

            // 3. Update status Kursi di JadwalSeat menjadi 'booked' (terjual permanen)
            $jumlahDiupdate = JadwalSeat::where('jadwal_tayang_id', $jadwalId)
                ->whereIn('nomor_kursi', $nomorKursi)
                ->where('status', 'locked') // Pastikan hanya kursi yang masih 'locked' yang diubah
                ->update([
                    'status' => 'booked',
                    'locked_until' => null // Hapus locked_until karena sudah terjual permanen
                ]);

            \Log::info("{$jumlahDiupdate} kursi di jadwal [{$jadwalId}] diubah menjadi 'booked'."); // Log untuk debugging
            
            DB::commit();

            return redirect()->route('user.pemesanan.show', $kode_pemesanan)
                ->with('success', 'Pembayaran berhasil dikonfirmasi! Tiket Anda sudah terbit.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal konfirmasi pembayaran Pemesanan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses pembayaran. Silakan coba lagi.');
        }
    }
    public function generatePayment(Request $request, $kode_pemesanan)
    {
        // 1. Ambil Data Pemesanan
        $pemesanan = Pemesanan::where('kode_pemesanan', $kode_pemesanan)
            ->with(['user', 'detailPemesanan']) // Pastikan Anda punya relasi user dan detailPemesanan
            ->firstOrFail();

        // 2. Konfigurasi Midtrans
        // Gunakan fungsi config() untuk membaca kunci dari .env
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION'); 
        Config::$isSanitized = true; 
        Config::$is3ds = true; 

        // 3. Persiapkan Parameter Transaksi
        $transaction_details = [
            'order_id'      => $pemesanan->kode_pemesanan, // HARUS UNIK
            'gross_amount'  => $pemesanan->total_harga,
        ];
        
        $item_details = [];
        // Buat detail item dari kursi yang dipesan
        $harga_per_tiket = $pemesanan->total_harga / $pemesanan->jumlah_tiket;
        
        foreach ($pemesanan->detailPemesanan as $index => $detail) {
            $item_details[] = [
                'id'       => $detail->nomor_kursi, 
                'price'    => $harga_per_tiket,
                'quantity' => 1,
                'name'     => "Tiket Kursi " . $detail->nomor_kursi,
            ];
        }

        $customer_details = [
            'first_name' => $pemesanan->user->name ?? 'Pelanggan', // Ambil nama user yang login
            'email'      => $pemesanan->user->email ?? 'pelanggan@example.com',
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'item_details'        => $item_details,
            'customer_details'    => $customer_details,
            // --- TAMBAHKAN URL PENGEMBALIAN INI ---
            // Pastikan APP_URL di .env sudah diset ke http://127.0.0.1:8000
            'finish_url' => route('user.history', [], true) . '?status=success&order_id=' . $kode_pemesanan,
            'unfinish_url' => route('user.pemesanan.show', ['kode_pemesanan' => $kode_pemesanan], true) . '?status=pending',
            'error_url' => route('user.pemesanan.show', ['kode_pemesanan' => $kode_pemesanan], true) . '?status=error',
            // ------------------------------------
        ];

        try {
            // 4. Panggil Midtrans Snap untuk mendapatkan URL Redirect
            $snapResponse = Snap::createTransaction($params);

            // Simpan token/url pembayaran (opsional, tapi disarankan)
            $pemesanan->snap_token = $snapResponse->token;
            $pemesanan->payment_url = $snapResponse->redirect_url;
            $pemesanan->save();
            
            // 5. Redirect Pengguna ke Halaman Pembayaran Midtrans
            return redirect($snapResponse->redirect_url);

        } catch (\Exception $e) {
            // Tangani error jika koneksi ke Midtrans gagal
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
        public function history()
    {
        // Mengambil pemesanan untuk user yang sedang login
        $pemesanans = Pemesanan::where('user_id', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->get();
                            
        return view('user.history', compact('pemesanans')); 
        // Atau sesuai lokasi file blade Anda, misalnya 'history'
    }
}