<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buat satu akun admin
        User::create([
            'name'     => 'AdminBioskop',
            'email'    => 'admin@bioskop.com',
            'password' => Hash::make('password'), // Password: password
            
        ]);
        
        $this->command->info('Akun Admin berhasil dibuat: admin@bioskop.com / password');
    }
}