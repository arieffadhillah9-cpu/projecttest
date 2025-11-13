<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'deskripsi',
        'durasi_menit',
        'sutradara',
        'genre',
        'tanggal_rilis',
        'poster_path',
        'is_tayang', // Tambahkan semua kolom yang Anda gunakan
    ];

    // Tambahkan relasi di sini (seperti yang kita bahas sebelumnya)
     public function jadwalTayangs()
    {
        // Pastikan nama method ini diubah menjadi plural (jadwalTayangs)
        // dan mengarah ke Model JadwalTayang.
        return $this->hasMany(JadwalTayang::class);
    }
}