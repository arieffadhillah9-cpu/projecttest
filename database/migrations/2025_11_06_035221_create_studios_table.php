<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Isi database/migrations/..._create_studios_table.php
Schema::create('studios', function (Blueprint $table) {
    $table->id();
    $table->string('nama', 50)->unique(); // Nama Studio (e.g., Studio 1, Dolby Atmos)
    $table->integer('kapasitas'); // Jumlah kursi total di studio
    $table->string('tipe_layar')->nullable(); // (e.g., 2D, 3D, IMAX)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
