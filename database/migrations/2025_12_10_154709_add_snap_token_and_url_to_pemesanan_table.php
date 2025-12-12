<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('pemesanan', function (Blueprint $table) {
        // Tambahkan dua kolom baru
        $table->string('snap_token')->nullable()->after('total_harga'); 
        $table->string('payment_url')->nullable()->after('snap_token');
    });
}

public function down()
{
    Schema::table('pemesanan', function (Blueprint $table) {
        // Hapus kedua kolom jika migration di-rollback
        $table->dropColumn(['snap_token', 'payment_url']);
    });
}
};
