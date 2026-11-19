<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->string('jenis_serangan')->nullable();
            $table->string('area_serangan')->nullable();
            $table->string('pola_serangan')->nullable();
            $table->string('role_utama')->nullable();
            $table->json('role_pendukung')->nullable();
            $table->json('role_tidak_direkomendasikan')->nullable();
        });

        DB::table('heroes')
            ->whereNotNull('gaya_permainan')
            ->whereNotIn('gaya_permainan', [
                'Assasin',
                'Petarung',
                'Suport',
                'Tank',
                'Alteleri',
                'Penembak Jitu',
            ])
            ->update(['gaya_permainan' => null]);
    }

    public function down(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_serangan',
                'area_serangan',
                'pola_serangan',
                'role_utama',
                'role_pendukung',
                'role_tidak_direkomendasikan',
            ]);
        });
    }
};
