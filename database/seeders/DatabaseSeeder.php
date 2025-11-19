<?php

namespace Database\Seeders;

use App\Models\User; // Pastikan ini ada di atas
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Tambahkan ini

class DatabaseSeeder extends Seeder
{
    
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat akun admin yang kredensialnya sudah Anda ketahui
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com', // <-- Email yang digunakan untuk login
            'password' => Hash::make('password123'), // <-- Password: password123
        ]);

        // 2. Membuat 10 user acak lainnya untuk data dummy (Opsional)
       
    }
}