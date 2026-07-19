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
        Schema::create('sentiment_results', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('news_article_id')
                  ->constrained('news_articles')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->decimal('positive_score', 5, 2)->default(0.00);
            $table->decimal('negative_score', 5, 2)->default(0.00);
            $table->decimal('neutral_score', 5, 2)->default(0.00);
            $table->decimal('total_score', 5, 2)->default(0.00);
            $table->string('sentiment_label', 20)->default('neutral')->index();
            $table->decimal('confidence', 4, 3)->default(1.000);
            $table->timestamp('processed_at')->nullable()->index();
            $table->string('analysis_version', 50)->default('1.0.0');
            $table->timestamps();

            $table->index('news_article_id');
            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiment_results');
    }
};
