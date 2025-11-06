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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            // Kolom utama untuk informasi film
            $table->string('judul', 255)->unique(); // Judul film, harus unik
            $table->text('deskripsi'); // Sinopsis atau deskripsi panjang
            $table->integer('durasi_menit'); // Durasi film dalam menit
            $table->string('sutradara', 100)->nullable(); // Nama sutradara (opsional)
            $table->string('genre', 100); // Genre (Anda bisa membuat tabel 'genres' terpisah nanti)
            $table->date('tanggal_rilis'); // Tanggal film dirilis
            $table->string('poster_path')->nullable(); // Path/URL ke gambar poster

            // Status tayang (untuk memfilter film yang sedang tayang)
            $table->boolean('is_tayang')->default(true); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};