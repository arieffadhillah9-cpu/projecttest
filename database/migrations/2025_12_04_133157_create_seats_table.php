<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');
            $table->string('nomor_kursi'); // contoh: A1, B10
            $table->unsignedSmallInteger('baris')->nullable(); // optional
            $table->unsignedSmallInteger('kolom')->nullable(); // optional
            $table->timestamps();

            $table->unique(['studio_id', 'nomor_kursi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
