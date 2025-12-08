<?php

namespace Database\Seeders;

use App\Models\User; // Pastikan ini ada di atas
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Tambahkan ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class, // Panggil Admin Seeder dulu
            DataBioskopSeeder::class, // Panggil Data Bioskop Seeder
            // Panggil Seeder lain yang mungkin Anda miliki
           SeatSeeder::class,

        ]);
    }
}