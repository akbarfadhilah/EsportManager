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
        Schema::create('team_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->foreignId('esport_team_id')->constrained('esport_teams')->onDelete('cascade');
            $table->integer('matches_played')->default(0);
            $table->integer('matches_won')->default(0);
            $table->integer('rounds_played')->default(0);
            $table->integer('rounds_won')->default(0);
            $table->integer('points')->default(0);
            $table->decimal('win_rate', 5, 2)->default(50.00);
            $table->timestamps();

            $table->index('tournament_id');
            $table->index('esport_team_id');
            $table->unique(['tournament_id', 'esport_team_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_statistics');
    }
};
