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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('nama_asli');
            $table->string('nickname_game');
            $table->text('deskripsi_pemain');
            $table->date('tanggal_lahir');
            $table->decimal('harga_pemain', 15, 2);
            $table->text('penghargaan_pemain')->nullable();
            $table->foreignId('esport_team_id')->nullable()->constrained('esport_teams')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
