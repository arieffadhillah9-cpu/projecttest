<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalTayang;

class JadwalTayangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * * Catatan: Seeder ini dikosongkan karena data Jadwal Tayang (yang merupakan 
     * data relasional kompleks antara Film dan Studio) lebih baik diisi 
     * secara manual melalui Admin Panel untuk menghindari konflik data 
     * seperti Unique Constraint Violation.
     */
    public function run(): void
    {
        // Pilihan: Tetap kosongkan tabel saat seeding untuk memastikan database bersih
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        JadwalTayang::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data Jadwal Tayang akan diisi melalui interface aplikasi (Admin Panel)
        // Contoh:
        /*
        JadwalTayang::create([
            //... data
        ]);
        */
    }
}