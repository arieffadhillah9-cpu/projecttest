<?php

// database/seeders/JadwalSeatSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalTayang; // Ambil semua jadwal
use App\Models\Seat; // Ambil kursi master
use App\Models\JadwalSeat; // Model yang akan diisi

class JadwalSeatSeeder extends Seeder
{
    public function run()
    {
        $jadwals = JadwalTayang::all();
        
        if ($jadwals->isEmpty()) {
            $this->command->warn("⚠ Tidak ada jadwal tayang. Jalankan DataBioskopSeeder dulu.");
            return;
        }

        foreach ($jadwals as $jadwal) {
            // Ambil semua kursi yang terkait dengan Studio jadwal ini
            $seats = Seat::where('studio_id', $jadwal->studio_id)->get();
            
            $dataToInsert = [];
            foreach ($seats as $seat) {
                $dataToInsert[] = [
                    'jadwal_tayang_id' => $jadwal->id,
                    'nomor_kursi' => $seat->nomor_kursi,
                    'status' => 'available', // Semua kursi di awal harus 'available'
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($dataToInsert)) {
                JadwalSeat::insert($dataToInsert);
                $this->command->info("Kursi untuk Jadwal ID {$jadwal->id} berhasil di-seeding ({$seats->count()} kursi).");
            }
        }
    }
}