<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalTayang extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Semua kolom bisa diisi kecuali 'id'

    protected $casts = [
        'tanggal' => 'date',
        // Jam seringkali lebih baik disimpan sebagai string (untuk format H:i)
    ];

    /**
     * Get the film that owns the JadwalTayang.
     */
    public function film(): BelongsTo
    {
        // Relasi JadwalTayang (Banyak) ke Film (Satu)
        return $this->belongsTo(Film::class);
    }

    /**
     * Get the studio that owns the JadwalTayang.
     */
    public function studio(): BelongsTo
    {
        // Relasi JadwalTayang (Banyak) ke Studio (Satu)
        return $this->belongsTo(Studio::class);
    }
}