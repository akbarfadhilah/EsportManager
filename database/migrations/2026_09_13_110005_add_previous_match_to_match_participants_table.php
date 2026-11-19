<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_participants', function (Blueprint $table) {
            if (!Schema::hasColumn('match_participants', 'previous_match_id')) {
                // For knock-out brackets: this participant slot = winner of another match
                $table->foreignId('previous_match_id')->nullable()->constrained('matches')->cascadeOnDelete()->after('esport_team_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('match_participants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('previous_match_id');
        });
    }
};