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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tournament');
            $table->string('penyelenggara');
            $table->decimal('total_hadiah', 15, 2);
            $table->text('deskripsi_tournament');
            $table->string('logo_tournament')->nullable();
            $table->integer('jumlah_slot');
            $table->string('negara_penyelenggara');
            $table->string('tempat_final');
            $table->enum('tier', [
                'S+ Tier', 
                'S Tier', 
                'A Tier', 
                'B Tier', 
                'C Tier', 
                'National Invitation', 
                'International Invitation'
            ]);
            $table->string('penghargaan_pemain')->nullable();
            $table->decimal('hadiah_penghargaan_pemain', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
