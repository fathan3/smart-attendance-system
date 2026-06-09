<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acara_id')
                  ->constrained('acara')
                  ->cascadeOnDelete();
            $table->string('nama');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->time('batas_absen_masuk')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};