<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->string('targeting')->nullable();
            $table->string('gaya_permainan')->nullable();
            $table->json('roles')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->dropColumn(['targeting', 'gaya_permainan', 'roles']);
        });
    }
};