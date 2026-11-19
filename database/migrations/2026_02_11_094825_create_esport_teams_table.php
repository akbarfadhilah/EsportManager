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
        Schema::create('esport_teams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tim');
            $table->text('deskripsi_tim');
            $table->string('logo_esport')->nullable();
            $table->string('pemilik');
            $table->string('ceo');
            $table->string('pelatih_utama');
            $table->string('pelatih_kedua')->nullable();
            $table->string('manager');
            $table->string('tempat_kota_gh');
            $table->string('analis')->nullable();
            $table->decimal('total_kekayaan', 15, 2)->default(0);
            $table->text('penghargaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esport_teams');
    }
};
