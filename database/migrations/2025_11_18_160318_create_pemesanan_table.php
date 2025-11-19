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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_tayangs')->onDelete('cascade');
            $table->string('kode_pemesanan')->unique(); // Contoh: TKT-20231118-0001
            $table->integer('jumlah_tiket');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', ['pending', 'paid', 'expired', 'canceled'])->default('pending');
            $table->timestamp('waktu_pemesanan')->useCurrent();
            $table->timestamp('waktu_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};