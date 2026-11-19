<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('esport_teams', function (Blueprint $table) {
            // Drop old string columns
            $table->dropColumn([
                'pemilik', 'ceo', 'pelatih_utama', 'pelatih_kedua', 
                'manager', 'analis', 'country'
            ]);

            // Add new FK columns
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            
            $table->foreignId('pemilik_id')->nullable()->constrained('players')->nullOnDelete();
            $table->foreignId('ceo_id')->nullable()->constrained('players')->nullOnDelete();
            $table->foreignId('pelatih_utama_id')->nullable()->constrained('players')->nullOnDelete();
            $table->foreignId('pelatih_kedua_id')->nullable()->constrained('players')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('players')->nullOnDelete();
            $table->foreignId('analis_id')->nullable()->constrained('players')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('esport_teams', function (Blueprint $table) {
            $table->string('pemilik')->default('');
            $table->string('ceo')->default('');
            $table->string('pelatih_utama')->default('');
            $table->string('pelatih_kedua')->nullable();
            $table->string('manager')->default('');
            $table->string('analis')->nullable();
            $table->string('country')->nullable();

            $table->dropForeign(['region_id']);
            $table->dropForeign(['pemilik_id']);
            $table->dropForeign(['ceo_id']);
            $table->dropForeign(['pelatih_utama_id']);
            $table->dropForeign(['pelatih_kedua_id']);
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['analis_id']);

            $table->dropColumn([
                'region_id', 'pemilik_id', 'ceo_id', 
                'pelatih_utama_id', 'pelatih_kedua_id', 'manager_id', 'analis_id'
            ]);
        });
    }
};
