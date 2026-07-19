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
        Schema::create('sentiment_matches', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('sentiment_result_id')
                  ->constrained('sentiment_results')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('matched_word');
            $table->string('word_type', 20)->default('neutral'); // positive, negative
            $table->decimal('word_score', 3, 2)->default(1.00);
            $table->integer('position')->default(0);
            $table->integer('frequency')->default(1);
            $table->timestamps();

            $table->index('sentiment_result_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sentiment_matches');
    }
};
