<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->string('kategori_personel')->default('Common');
            $table->string('role_utama')->nullable();
            $table->json('role_cadangan')->nullable();
            $table->json('role_kurang_kompeten')->nullable();
            $table->json('gaya_permainan')->nullable();
            $table->json('targeting_urut')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'kategori_personel',
                'role_utama',
                'role_cadangan',
                'role_kurang_kompeten',
                'gaya_permainan',
                'targeting_urut',
            ]);
        });
    }
};
