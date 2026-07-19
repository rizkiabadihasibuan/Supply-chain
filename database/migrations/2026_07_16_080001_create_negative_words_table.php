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
        Schema::create('negative_words', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('dictionary_id')
                  ->constrained('sentiment_dictionaries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('word')->index();
            $table->decimal('score', 3, 2)->default(1.00);
            $table->string('language', 5)->default('en')->index();
            $table->string('description')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->timestamps();

            $table->index('dictionary_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negative_words');
    }
};
