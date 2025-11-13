<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;
    
    // Sesuaikan dengan kolom di migration create_studios_table Anda
    protected $fillable = [
        'nama', 
        'kapasitas', 
        'tipe_layar',
        // Tambahkan kolom lain jika ada
    ];
    
    // Anda bisa menambahkan relasi ke JadwalTayang di sini nanti
    // public function jadwalTayang() {
    //     return $this->hasMany(JadwalTayang::class);
    // }
    public function jadwalTayangs()
    {
        return $this->hasMany(JadwalTayang::class);
    }
}