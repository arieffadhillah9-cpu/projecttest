<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kursi extends Model
{
    use HasFactory;

    protected $fillable = [
        'studio_id',
        'nomor_kursi',
    ];

    /**
     * Relasi: Kursi dimiliki oleh satu Studio.
     */
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class, 'studio_id');
    }
}