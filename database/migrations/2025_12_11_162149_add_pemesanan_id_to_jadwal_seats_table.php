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
        Schema::table('jadwal_seats', function (Blueprint $table) {
            // Tambahkan kolom pemesanan_id
            $table->foreignId('pemesanan_id')->nullable()->constrained('pemesanan')->after('locked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_seats', function (Blueprint $table) {
            // Drop foreign key (opsional, tapi disarankan)
            $table->dropForeign(['pemesanan_id']);
            // Hapus kolom pemesanan_id
            $table->dropColumn('pemesanan_id');
        });
    }
};