<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_tayang_id')->constrained('jadwal_tayangs')->onDelete('cascade');
            $table->foreignId('seat_id')->nullable()->constrained('seats')->onDelete('cascade');
            $table->string('nomor_kursi'); // copy dari seats.nomor_kursi untuk query cepat
            $table->enum('status', ['available','locked','booked'])->default('available');
            $table->timestamp('locked_until')->nullable(); // kalau mau mekanisme lock sementara
            $table->timestamps();

            $table->unique(['jadwal_tayang_id', 'nomor_kursi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_seats');
    }
};
