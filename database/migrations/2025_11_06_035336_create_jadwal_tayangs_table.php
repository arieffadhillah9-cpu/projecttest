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
       // Isi database/migrations/..._create_jadwal_tayangs_table.php
    // Isi database/migrations/..._create_jadwal_tayangs_table.php

Schema::create('jadwal_tayangs', function (Blueprint $table) {
    $table->id();

    // Pastikan ini menunjuk ke tabel 'films'
    $table->foreignId('film_id')->constrained('films')->onDelete('cascade'); 
    
    // Pastikan ini menunjuk ke tabel 'studios'
    $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade'); 
    
    $table->dateTime('waktu_tayang')->unique();
    $table->decimal('harga_tiket', 10, 2); 
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tayangs');
    }
};
