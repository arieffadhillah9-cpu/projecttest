<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Film;
use App\Models\Studio;
use App\Models\JadwalTayang;
use Carbon\Carbon;

class DataBioskopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Buat Film Dummy (Menggunakan kolom: judul, deskripsi, durasi_menit, sutradara, genre, tanggal_rilis, is_tayang)
        $filmAksi = Film::create([
            'judul'          => 'Aksi Luar Angkasa 7: Kebangkitan',
            'deskripsi'      => 'Petualangan tim elit di galaksi yang belum terjamah untuk menemukan sumber energi baru. Penuh ledakan dan visual memukau.',
            'durasi_menit'   => 120, // Durasi dalam menit
            'sutradara'      => 'Rian Johnson',
            'genre'          => 'Sci-Fi, Aksi',
            'tanggal_rilis'  => '2025-11-01',
            'poster_path'    => 'placeholder/poster_aksi.jpg', // Dummy path
            'is_tayang'      => true, 
        ]);

        $filmHoror = Film::create([
            'judul'          => 'Horor Malam Jumat: Balas Dendam',
            'deskripsi'      => 'Sekelompok remaja terjebak dalam misteri rumah tua yang terbengkalai dengan sejarah kelam. Berdasarkan kisah nyata yang menyeramkan.',
            'durasi_menit'   => 95, // Durasi dalam menit
            'sutradara'      => 'James Wan',
            'genre'          => 'Horor, Misteri',
            'tanggal_rilis'  => '2025-12-01',
            'poster_path'    => 'placeholder/poster_horor.jpg', // Dummy path
            'is_tayang'      => true,
        ]);

        // 2. Buat Studio Dummy (Menggunakan kolom: nama, kapasitas, tipe_layar)
        $studioRegular = Studio::create([
            'nama'       => 'Studio 1 - Reguler', 
            'kapasitas'  => 100,
            'tipe_layar' => '2D Digital'
        ]);
        
        $studioVIP = Studio::create([
            'nama'       => 'Studio 2 - Premiere', 
            'kapasitas'  => 50,
            'tipe_layar' => 'Dolby Atmos'
        ]);

        // 3. Buat Jadwal Tayang yang VALID (di masa depan)
        // Tanggal Hari Ini + 3 hari (agar valid saat diuji)
        $tanggalTayang = Carbon::now()->addDays(3)->toDateString(); 
        
        // Jadwal untuk Film Aksi di Studio Reguler
        JadwalTayang::create([
            'film_id'   => $filmAksi->id,
            'studio_id' => $studioRegular->id,
            'tanggal'   => $tanggalTayang, 
            'jam_mulai' => '14:00:00',
            'harga'     => 45000,
        ]);

        // Jadwal untuk Film Aksi di Studio Premiere (Harga lebih mahal)
        JadwalTayang::create([
            'film_id'   => $filmAksi->id,
            'studio_id' => $studioVIP->id,
            'tanggal'   => $tanggalTayang, 
            'jam_mulai' => '17:30:00',
            'harga'     => 75000,
        ]);
        
        // Jadwal untuk Film Horor di Studio Reguler (Hari berikutnya)
        $tanggalTayang2 = Carbon::now()->addDays(4)->toDateString(); 
        
        JadwalTayang::create([
            'film_id'   => $filmHoror->id,
            'studio_id' => $studioRegular->id,
            'tanggal'   => $tanggalTayang2, 
            'jam_mulai' => '20:00:00',
            'harga'     => 45000,
        ]);

        $this->command->info("Data Film, Studio, dan Jadwal Tayang berhasil dibuat dan sesuai dengan skema migrasi.");
    }
}