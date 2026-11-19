<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            if (!Schema::hasColumn('tournaments', 'format')) {
                $table->enum('format', ['standard', 'knockout'])->default('standard')->after('tier');
            }
            if (!Schema::hasColumn('tournaments', 'bo_format')) {
                $table->enum('bo_format', ['BO3', 'BO5', 'BO7'])->default('BO3')->after('format');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn(['format', 'bo_format']);
        });
    }
};