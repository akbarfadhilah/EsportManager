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
        Schema::create('tournament_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->integer('rank');
            $table->decimal('prize_amount', 15, 2);
            $table->timestamps();
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->enum('status', ['upcoming', 'ongoing', 'completed'])->default('upcoming');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::dropIfExists('tournament_prizes');
    }
};
