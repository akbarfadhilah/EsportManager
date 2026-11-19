<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('round_lineups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('hero_id')->constrained('heroes')->cascadeOnDelete();
            $table->foreignId('esport_team_id')->nullable()->constrained('esport_teams')->nullOnDelete();
            $table->timestamps();

            // A player can only be fielded once per round
            $table->unique(['round_id', 'player_id']);
            // Every hero must be unique within a single round (across both teams)
            $table->unique(['round_id', 'hero_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('round_lineups');
    }
};