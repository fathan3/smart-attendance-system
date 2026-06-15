<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')
                ->constrained('agenda')
                ->cascadeOnDelete();
            $table->string('rfid_uid', 32)->nullable();
            $table->datetime('waktu_masuk')->nullable();
            $table->datetime('waktu_pulang')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'tidak_hadir', 'izin'])
                ->default('tidak_hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('rfid_uid');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
