<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop player_roles table as requested
        Schema::dropIfExists('player_roles');

        // 2. Modify players table
        Schema::table('players', function (Blueprint $table) {
            // Drop removed columns
            $table->dropColumn(['harga_pemain', 'penghargaan_pemain', 'nationality']);
            
            // Add new columns
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            
            // Add jabatan matching the various personnel roles
            $table->enum('jabatan', [
                'Player',
                'Owner',
                'CEO',
                'Head Coach',
                'Assistant Coach',
                'Manager',
                'Analyst',
                'Ketua Persatuan Game',
                'Pelatih Timnas'
            ])->default('Player');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->decimal('harga_pemain', 15, 2)->default(0);
            $table->text('penghargaan_pemain')->nullable();
            $table->string('nationality')->nullable();
            
            $table->dropForeign(['region_id']);
            $table->dropColumn(['region_id', 'jabatan']);
        });

        Schema::create('player_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->enum('role', ['ROAMER', 'Jungler', 'GoldLane', 'ExpLane', 'MidLane']);
            $table->timestamps();
        });
    }
};
