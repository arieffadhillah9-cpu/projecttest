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
        Schema::create('jadwal_tayangs', function (Blueprint $table) {
            $table->id();

            // Kunci Asing (Foreign Key) ke tabel films
            // Memastikan data di kolom ini adalah ID yang valid di tabel films
            $table->foreignId('film_id')->constrained('films')->onDelete('cascade');
            
            // Kunci Asing (Foreign Key) ke tabel studios
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->unsignedInteger('harga'); // Harga tiket, harus positif
            
            // Kolom unik ganda: Tidak boleh ada film yang sama di studio yang sama pada jam dan tanggal yang persis sama
            $table->unique(['studio_id', 'tanggal', 'jam_mulai'], 'unique_schedule');
            
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
