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
            
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('risk_score_id')
                  ->constrained('risk_scores')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->decimal('total_risk_score', 5, 2)->default(0.00);
            $table->string('risk_level', 50)->default('Low');
            $table->date('calculated_date')->index();
            $table->timestamps();

            $table->index('country_id');
            $table->index('risk_score_id');
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
