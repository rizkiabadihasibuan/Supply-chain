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
        Schema::table('countries', function (Blueprint $table) {
            $table->decimal('exchange_rate', 15, 6)->nullable()->after('currency_symbol');
            $table->string('exchange_rate_base', 10)->default('USD')->after('exchange_rate');
            $table->json('exchange_rate_history')->nullable()->after('exchange_rate_base');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn([
                'exchange_rate',
                'exchange_rate_base',
                'exchange_rate_history'
            ]);
        });
    }
};
