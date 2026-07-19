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
        Schema::table('risk_scores', function (Blueprint $table) {
            $table->foreignId('snapshot_id')
                  ->nullable()
                  ->constrained('risk_snapshots')
                  ->cascadeOnDelete();
                  
            $table->decimal('economic_score', 5, 2)->default(0.00)->after('weather_score');
            $table->decimal('logistics_score', 5, 2)->default(0.00)->after('political_score');
            $table->decimal('overall_score', 5, 2)->default(0.00)->after('logistics_score')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_scores', function (Blueprint $table) {
            $table->dropForeign(['snapshot_id']);
            $table->dropColumn(['snapshot_id', 'economic_score', 'logistics_score', 'overall_score']);
        });
    }
};
