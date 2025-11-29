<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\StudioSeeder; // Tambahkan import untuk StudioSeeder

class DatabaseSeeder extends Seeder
{
    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat akun admin
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com', // <-- Email yang digunakan untuk login
            'password' => Hash::make('password123'), // <-- Password: password123
        ]);

        // 2. Membuat 10 user acak lainnya untuk data dummy (Opsional)
        // User::factory(10)->create(); 
        
        // 3. Panggil Seeder untuk data STUDIO
        $this->call(StudioSeeder::class); 
        $this->call(FilmSeeder::class);
        $this->call(JadwalTayangSeeder::class);
        
        // Panggil seeder lain di sini (FilmSeeder, JadwalTayangSeeder, dll.)
    }
}