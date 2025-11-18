<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // PASTIKAN BARIS INI ADA
        $this->call([
            InitialDataSeeder::class,
        ]);
    }
}