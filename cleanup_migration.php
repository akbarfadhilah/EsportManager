<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::dropIfExists('tournament_prizes');
    Schema::dropIfExists('tournament_prizes_table_and_add_status_to_tournaments');
    
    if (Schema::hasColumn('tournaments', 'status')) {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
    
    DB::table('migrations')->where('migration', 'like', '%create_tournament_prizes%')->delete();
    
    echo "Cleanup successful.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
exit();
