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
        Schema::table('risk_histories', function (Blueprint $table) {
            $table->decimal('overall_score', 5, 2)->default(0.00)->after('risk_score_id')->index();
            $table->timestamp('recorded_at')->useCurrent()->after('risk_level')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_histories', function (Blueprint $table) {
            $table->dropColumn(['overall_score', 'recorded_at']);
        });
    }
};
