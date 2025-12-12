<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pemesanan; // Pastikan model ini ada
use App\Models\JadwalSeat; // Pastikan model ini ada
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log; // Pastikan ini ada dan setelah namespace

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        // --- TAMBAHKAN INI SEGERA DI AWAL FUNGSI ---
    \Log::info('Webhook Midtrans Diterima!', ['data' => $request->all()]);
    // ---------------------------------------------
        // 1. Konfigurasi Midtrans Server Key
        // PENTING: Gunakan server key untuk verifikasi
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        
        try {
            // Membuat objek notifikasi Midtrans
            $notification = new Notification();
        } catch (\Exception $e) {
            // Jika ada error saat inisialisasi notifikasi
            return response()->json(['message' => 'Notification error'], 400);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;
        
        // 2. Cari Pemesanan berdasarkan Order ID
        $pemesanan = Pemesanan::where('kode_pemesanan', $orderId)->first();

        if (!$pemesanan) {
            // Notifikasi untuk Order ID yang tidak dikenal
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. Proses Status Berdasarkan Notifikasi Midtrans
        
        // Kondisi sukses (VA/QRIS Settlement, Kartu Kredit Capture)
        if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {
            
            // Cek untuk menghindari pemrosesan duplikat
            if ($pemesanan->status != 'paid') {
                
                // Update status pemesanan
                $pemesanan->status = 'paid';
                $pemesanan->waktu_pembayaran = now();
                $pemesanan->save();

                // *** Update status Kursi (Locked -> Booked) ***
                JadwalSeat::where('pemesanan_id', $pemesanan->id)
                    ->update([
                        'status' => 'booked', 
                        'locked_until' => null // Kursi sudah dibooking permanen
                    ]);
            }
        
        } else if ($transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Kondisi gagal/kadaluarsa
            
            if ($pemesanan->status != 'expired') {
                $pemesanan->status = 'expired';
                $pemesanan->save();
                
                // *** Bebaskan Kursi (Locked -> Available) ***
                JadwalSeat::where('pemesanan_id', $pemesanan->id)
                    ->update([
                        'status' => 'available', 
                        'pemesanan_id' => null, // Hapus kaitan ke pemesanan yang expired
                        'locked_until' => null
                    ]);
            }
        }
        
        // 4. Midtrans harus menerima HTTP 200 OK sebagai balasan
        return response()->json(['message' => 'Notification processed successfully']);
    }
}