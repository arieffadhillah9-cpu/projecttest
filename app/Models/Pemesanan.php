<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini

class Pemesanan extends Model
{
    use HasFactory;

    // Catatan: Mengubah 'pemesanan' menjadi 'pemesanans' agar sesuai dengan konvensi database Laravel
    // Jika Anda telah menggunakan 'pemesanan' di migrasi, pertahankan saja: protected $table = 'pemesanan';
    // Namun, di file migrasi sebelumnya kita membuat tabel 'pemesanans', jadi saya hilangkan baris ini:
    // protected $table = 'pemesanan'; 
    protected $table = 'pemesanans'; // Asumsi tabel Anda bernama 'pemesanans' (sesuai standar Laravel)

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'kode_pemesanan',
        'jumlah_tiket',
        'total_harga',
        'status',
        'waktu_pembayaran',
    ];

    protected $casts = [
        'waktu_pemesanan' => 'datetime', // Pastikan kolom ini ada di migrasi pemesanan
        'waktu_pembayaran' => 'datetime',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); 
    }

    // Relasi ke Jadwal Tayang
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalTayang::class, 'jadwal_id'); 
    }

    // Relasi ke Detail Pemesanan (Kursi)
    // Tipe relasi HasMany ditambahkan
    public function detailPemesanan(): HasMany
    {
        return $this->hasMany(DetailPemesanan::class);
    }
}