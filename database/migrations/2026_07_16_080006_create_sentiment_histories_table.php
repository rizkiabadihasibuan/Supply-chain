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
        Schema::create('sentiment_histories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('sentiment_result_id')
                  ->constrained('sentiment_results')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->decimal('avg_sentiment_score', 4, 2)->default(0.00);
            $table->date('recorded_date')->index();
            $table->string('analysis_version', 50)->nullable();
            $table->timestamps();

            $table->index('country_id');
            $table->index('sentiment_result_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiment_histories');
    }
};
