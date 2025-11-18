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
        Schema::create('kursis', function (Blueprint $table) {
            $table->id();

            // Relasi ke Studio mana kursi ini berada
            $table->foreignId('studio_id')
                  ->constrained('studios')
                  ->onDelete('cascade');

            $table->string('nomor_kursi'); // Contoh: A1, B12, C5
            
            // Kolom gabungan untuk memastikan satu kursi hanya ada satu kali di satu studio
            $table->unique(['studio_id', 'nomor_kursi']);

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
        Schema::dropIfExists('kursis');
    }
};
