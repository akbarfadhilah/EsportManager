<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->enum('jabatan', [
                'None',
                'Player',
                'Owner',
                'CEO',
                'Head Coach',
                'Assistant Coach',
                'Manager',
                'Analyst',
                'Ketua Persatuan Game',
                'Pelatih Timnas',
            ])->default('None')->change();
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->enum('jabatan', [
                'Player',
                'Owner',
                'CEO',
                'Head Coach',
                'Assistant Coach',
                'Manager',
                'Analyst',
                'Ketua Persatuan Game',
                'Pelatih Timnas',
            ])->default('Player')->change();
        });
    }
};