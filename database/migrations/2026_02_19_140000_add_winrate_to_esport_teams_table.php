<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('esport_teams', function (Blueprint $table) {
            $table->decimal('winrate', 5, 2)->default(50.00)->after('total_kekayaan');
        });
    }

    public function down(): void
    {
        Schema::table('esport_teams', function (Blueprint $table) {
            $table->dropColumn('winrate');
        });
    }
};
