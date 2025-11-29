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
        Schema::create('detail_pemesanans', function (Blueprint $table) {
            $table->id();

            // Kunci asing ke tabel 'pemesanans' (Header Transaksi)
            // Ini WAJIB untuk menghubungkan detail kursi ke transaksi utama.
            $table->foreignId('pemesanan_id')
                  ->constrained('pemesanans')
                  ->onDelete('cascade'); // Jika Pemesanan dihapus, detailnya juga ikut terhapus.

            // Nomor Kursi Spesifik (Detail Kunci)
            // Contoh: 'A1', 'C15'. Digunakan untuk pengecekan ketersediaan (anti-double booking).
            $table->string('nomor_kursi', 5);

            // Harga Satuan Tiket saat pemesanan terjadi (untuk audit/keandalan data)
            $table->unsignedInteger('harga_satuan');

            $table->timestamps();

            // Tambahkan index unik gabungan untuk mencegah double booking pada satu transaksi yang sama
            // Meskipun logika anti-double booking di controller sudah mencakup ini,
            // batasan database ini memberikan lapisan keamanan data tambahan.
            // UNIK: tidak boleh ada nomor kursi yang sama dalam satu pemesanan_id
            $table->unique(['pemesanan_id', 'nomor_kursi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pemesanans');
    }
};