<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    
    // Pastikan is_tayang, durasi_menit, dan poster_path ada di $fillable
    protected $fillable = [
        'judul',
        'deskripsi',
        'durasi_menit',
        'sutradara',
        'genre',
        'tanggal_rilis',
        'poster_path',
        'is_tayang', // Penting untuk filter di HomepageController
    ];

    // Casting untuk is_tayang agar selalu diperlakukan sebagai boolean
    protected $casts = [
        'is_tayang' => 'boolean',
        'tanggal_rilis' => 'date',
    ];
}