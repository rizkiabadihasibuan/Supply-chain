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
        Schema::create('risk_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->decimal('weather_risk_score', 5, 2)->default(0.00);
            $table->decimal('inflation_risk_score', 5, 2)->default(0.00);
            $table->decimal('currency_risk_score', 5, 2)->default(0.00);
            $table->decimal('political_risk_score', 5, 2)->default(0.00);
            $table->decimal('total_risk_score', 5, 2)->default(0.00);
            $table->string('risk_level', 20)->default('low');
            $table->timestamp('calculated_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_histories');
    }
};
