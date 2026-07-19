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
            
            $table->foreignId('country_id')
                  ->unique()
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('classification_id')
                  ->constrained('risk_classifications')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->decimal('weather_score', 5, 2)->default(0.00);
            $table->decimal('inflation_score', 5, 2)->default(0.00);
            $table->decimal('currency_score', 5, 2)->default(0.00);
            $table->decimal('political_score', 5, 2)->default(0.00);
            $table->decimal('final_risk_score', 5, 2)->default(0.00)->index();
            $table->string('risk_level', 50)->default('Low')->index();
            $table->timestamp('calculated_at')->nullable()->index();
            $table->string('source_version')->nullable();
            $table->timestamps();

            $table->index('country_id');
            $table->index('classification_id');
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
