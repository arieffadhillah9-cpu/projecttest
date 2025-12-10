<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Seat;
use App\Models\JadwalSeat;
use Illuminate\Support\Facades\DB;

class JadwalTayang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
    'jam_mulai' => 'datetime:H:i',
    'harga',
    ];

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function jadwalSeats()
    {
        return $this->hasMany(JadwalSeat::class, 'jadwal_tayang_id');
    }
    public function seats()
{
    return $this->hasMany(JadwalSeat::class, 'jadwal_tayang_id');
}

    // Saat jadwal dibuat, generate jadwal_seats berdasarkan seats pada studio
    protected static function booted()
    {
        static::created(function ($jadwal) {
            // cari semua template seats milik studio
            $seats = Seat::where('studio_id', $jadwal->studio_id)->get();

            // jika tidak ada template seat, kita bisa generate berdasarkan kapasitas (fallback)
            if ($seats->isEmpty()) {
                // fallback: buat seats otomatis berdasarkan kapasitas studio (baris/kolom kasar)
                $kapasitas = $jadwal->studio->kapasitas ?? 50;
                // buat nomor seperti A1.. (simple linear)
                $generated = [];
                for ($i = 1; $i <= $kapasitas; $i++) {
                    $nomor = 'S' . $i;
                    $generated[] = [
                        'jadwal_tayang_id' => $jadwal->id,
                        'seat_id' => null,
                        'nomor_kursi' => $nomor,
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('jadwal_seats')->insert($generated);
            } else {
                $insert = [];
                foreach ($seats as $s) {
                    $insert[] = [
                        'jadwal_tayang_id' => $jadwal->id,
                        'seat_id' => $s->id,
                        'nomor_kursi' => $s->nomor_kursi,
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('jadwal_seats')->insert($insert);
            }
        });
    }
}
