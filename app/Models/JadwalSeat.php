<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSeat extends Model
{
    use HasFactory;

    protected $table = 'jadwal_seats';

    protected $fillable = [
        'jadwal_tayang_id',
        'seat_id',
        'nomor_kursi',
        'status',
        'locked_until',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalTayang::class, 'jadwal_tayang_id');
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
