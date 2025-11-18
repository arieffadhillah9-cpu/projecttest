<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();

            // Relasi ke Jadwal Tayang yang dibeli
            $table->foreignId('jadwal_tayang_id')
                  ->constrained('jadwal_tayangs')
                  ->onDelete('cascade');

            // Relasi ke User (Diasumsikan sudah ada tabel 'users' untuk pembeli)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // --- Detail Transaksi ---
            $table->string('kode_pemesanan')->unique(); // Contoh: TGB20241115001
            $table->integer('jumlah_tiket');
            $table->unsignedBigInteger('total_harga');

            // --- Detail Kursi ---
            // Menyimpan daftar kursi yang dipesan dalam bentuk teks/JSON string
            $table->text('kursi_terpilih'); 

            // --- Status Pembayaran ---
            $table->enum('status_pembayaran', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('waktu_pembayaran')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanans');
    }
};