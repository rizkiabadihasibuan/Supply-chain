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
        Schema::create('risk_components', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('risk_score_id')
                  ->constrained('risk_scores')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('category_id')
                  ->constrained('risk_categories')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('indicator_name');
            $table->decimal('raw_value', 10, 2)->default(0.00);
            $table->decimal('weight', 4, 2)->default(0.00);
            $table->decimal('weighted_score', 5, 2)->default(0.00);
            $table->timestamps();

            $table->index('risk_score_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_components');
    }
};
