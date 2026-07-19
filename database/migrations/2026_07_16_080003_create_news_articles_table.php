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
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('source_id')
                  ->constrained('news_sources')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('category_id')
                  ->constrained('news_categories')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('url', 2048);
            $table->string('image_url', 2048)->nullable();
            $table->string('author')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('language', 10)->default('en')->index();
            $table->string('sentiment_status', 20)->default('neutral')->index(); // Future ready
            $table->decimal('risk_score_reference', 5, 2)->nullable(); // Future ready
            $table->timestamps();

            // Explicit indexes
            $table->index('country_id');
            $table->index('source_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
