<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_tayang_id',
        'user_id',
        'kode_pemesanan',
        'jumlah_tiket',
        'total_harga',
        'kursi_terpilih',
        'status_pembayaran',
        'waktu_pembayaran',
    ];

    /**
     * Relasi: Pemesanan dimiliki oleh satu Jadwal Tayang.
     */
    public function jadwalTayang(): BelongsTo
    {
        return $this->belongsTo(JadwalTayang::class, 'jadwal_tayang_id');
    }

    /**
     * Relasi: Pemesanan dimiliki oleh satu User (Pembeli).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}