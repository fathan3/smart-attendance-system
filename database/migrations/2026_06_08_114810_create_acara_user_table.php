<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acara_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acara_id')
                  ->constrained('acara')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('divisi_id')
                  ->constrained('divisi')
                  ->restrictOnDelete();
            $table->timestamps();

            $table->unique(['acara_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acara_user');
    }
};