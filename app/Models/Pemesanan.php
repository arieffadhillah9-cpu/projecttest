<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';

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
        'waktu_pemesanan' => 'datetime',
        'waktu_pembayaran' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        // Sesuaikan dengan model User Anda (misalnya App\Models\User::class)
        return $this->belongsTo(User::class); 
    }

    // Relasi ke Jadwal Tayang
    public function jadwal()
    {
        return $this->belongsTo(JadwalTayang::class, 'jadwal_id'); // Pastikan menggunakan JadwalTayang
    }

    // Relasi ke Detail Pemesanan (Kursi)
    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class);
    }
}