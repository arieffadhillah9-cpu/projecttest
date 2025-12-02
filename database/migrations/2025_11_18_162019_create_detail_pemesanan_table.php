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
        // Jika tabel detail_pemesanan sudah terlanjur dibuat, Anda perlu menjalankan `php artisan migrate:rollback`
        // lalu hapus migrasi lama dan ganti dengan kode ini, atau buat migrasi baru untuk menambahkan kolom.
        
        Schema::create('detail_pemesanan', function (Blueprint $table) {
            $table->id();
            
            // Kolom pemesanan_id (relasi ke transaksi utama)
            $table->foreignId('pemesanan_id')->constrained('pemesanan')->onDelete('cascade');
            
            // Kolom JADWAL_ID (PENTING: untuk mengunci kursi pada jadwal tertentu)
            $table->foreignId('jadwal_id')->constrained('jadwal_tayangs')->onDelete('cascade');

            $table->string('nomor_kursi'); // Contoh: A1, B10, dll.
            $table->timestamps();

            // Unique Constraint Kritis: 
            // Kombinasi jadwal_id dan nomor_kursi harus UNIK.
            // Ini memastikan Kursi A1 pada Jadwal X tidak dapat dipesan dua kali,
            // tidak peduli oleh transaksi mana (pemesanan_id).
            $table->unique(['jadwal_id', 'nomor_kursi']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemesanan');
    }
};