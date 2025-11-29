<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini

class DetailPemesanan extends Model
{
    use HasFactory;

    // Nama tabel disesuaikan dengan migrasi (detail_pemesanans)
    protected $table = 'detail_pemesanans';

    protected $fillable = [
        'pemesanan_id',
        'nomor_kursi',
        'harga_satuan', // <<< --- KOLOM BARU ditambahkan ke $fillable
    ];

    // Relasi ke Pemesanan (Tabel Header)
    // Tipe relasi BelongsTo ditambahkan
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class);
    }
}