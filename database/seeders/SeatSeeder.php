<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua studio
        $studios = Studio::all();

        if ($studios->count() === 0) {
            $this->command->warn("⚠ Tidak ada studio ditemukan. Jalankan seeder Studio atau buat studio dulu.");
            return;
        }

        foreach ($studios as $studio) {

            $this->command->info("Membuat kursi untuk Studio: {$studio->nama}");

            // Contoh layout 4 baris × 10 kursi per baris
            foreach (range('A', 'D') as $row) {
                foreach (range(1, 10) as $num) {
                    Seat::create([
                        'studio_id' => $studio->id,
                        'nomor_kursi' => $row . $num,
                    ]);
                }
            }
        }

        $this->command->info("✔ Semua kursi studio berhasil dibuat.");
    }
}
