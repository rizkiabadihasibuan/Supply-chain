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
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->decimal('weather_risk_score', 5, 2);
            $table->decimal('inflation_risk_score', 5, 2);
            $table->decimal('political_risk_score', 5, 2);
            $table->decimal('currency_risk_score', 5, 2);
            $table->decimal('total_risk_score', 5, 2);
            $table->enum('risk_level', ['Low', 'Medium', 'High'])->default('Low');
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
