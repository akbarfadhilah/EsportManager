<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_negara');
            $table->foreignId('ketua_persatuan_game_id')->nullable()->constrained('players')->nullOnDelete();
            $table->foreignId('pelatih_timnas_id')->nullable()->constrained('players')->nullOnDelete();
            $table->string('foto_negara')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
