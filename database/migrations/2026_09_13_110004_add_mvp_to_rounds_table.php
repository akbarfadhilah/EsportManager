<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rounds', function (Blueprint $table) {
            if (!Schema::hasColumn('rounds', 'mvp_player_id')) {
                $table->foreignId('mvp_player_id')->nullable()->constrained('players')->nullOnDelete()->after('winner_team_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rounds', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mvp_player_id');
        });
    }
};