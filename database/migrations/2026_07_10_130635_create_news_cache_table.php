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
        Schema::create('news_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('url');
            $table->string('source_name')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->enum('sentiment_label', ['Positive', 'Neutral', 'Negative'])->default('Neutral');
            $table->integer('positive_score')->default(0);
            $table->integer('negative_score')->default(0);
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_cache');
    }
};
