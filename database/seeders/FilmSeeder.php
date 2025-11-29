<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Film; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan Foreign Key Check sementara untuk TRUNCATE
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Film::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $films = [
            [
                'judul' => 'The Laravel Journey',
                'deskripsi' => 'Kisah epik tentang seorang programmer yang berjuang menguasai framework Laravel, menghadapi bug, dan akhirnya mencapai kemenangan di dunia web development. Film ini menunjukkan pentingnya struktur Model, View, Controller (MVC) dalam membangun aplikasi modern.',
                'durasi_menit' => 125, 
                'sutradara' => 'Taylor Otwell',
                'genre' => 'Dokumenter Teknologi',
                'tanggal_rilis' => Carbon::create(2024, 7, 15),
                'poster_path' => 'https://placehold.co/400x600/1e293b/ffffff?text=FILM+LARAVEL',
                'is_tayang' => true,
            ],
            [
                'judul' => 'Code of Silence',
                'deskripsi' => 'Film thriller tentang kerahasiaan data dan perjuangan seorang ahli keamanan siber melawan sindikat peretasan global. Ketegangan dimulai saat server diretas dan sang pahlawan harus menggunakan keahlian kodenya untuk menyelamatkan dunia.',
                'durasi_menit' => 98,
                'sutradara' => 'Anna Security',
                'genre' => 'Thriller, Kriminal',
                'tanggal_rilis' => Carbon::create(2023, 11, 10),
                'poster_path' => 'https://placehold.co/400x600/34d399/1e293b?text=CODE+OF+SILENCE', 
                'is_tayang' => true,
            ],
            [
                'judul' => 'Starlight Express: The Final Frontier',
                'deskripsi' => 'Petualangan fantasi di luar angkasa dengan visual yang memukau. Mencari planet baru di tengah badai meteor dan ancaman alien. Cocok untuk semua usia yang menyukai fiksi ilmiah.',
                'durasi_menit' => 140,
                'sutradara' => 'Dr. Sci-Fi',
                'genre' => 'Fiksi Ilmiah, Petualangan',
                'tanggal_rilis' => Carbon::create(2025, 1, 1),
                'poster_path' => 'https://placehold.co/400x600/818cf8/1e293b?text=STARLIGHT+EXP', 
                'is_tayang' => false, // Contoh film yang sudah tidak tayang
            ],
        ];

        foreach ($films as $film) {
            Film::create($film);
        }
    }
}