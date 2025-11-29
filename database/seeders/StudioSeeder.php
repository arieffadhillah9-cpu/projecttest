<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio; // Pastikan model Studio di-import
use Illuminate\Support\Facades\DB;

class StudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan Foreign Key Checks sementara (opsional, tapi disarankan)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Studio::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $studios = [
            [
                'nama' => 'Reguler 1',
                'kapasitas' => 120,
                'tipe_layar' => 'Standard Digital',
            ],
            [
                'nama' => 'Reguler 2',
                'kapasitas' => 100,
                'tipe_layar' => 'Standard Digital',
            ],
            [
                'nama' => 'Premiere Gold',
                'kapasitas' => 50,
                'tipe_layar' => 'Premium Digital 3D',
            ],
            [
                'nama' => 'IMAX',
                'kapasitas' => 180,
                'tipe_layar' => 'IMAX Laser',
            ],
            [
                'nama' => 'Studio Kids',
                'kapasitas' => 80,
                'tipe_layar' => 'Standard Digital',
            ],
        ];

        foreach ($studios as $studio) {
            Studio::create($studio);
        }
    }
}