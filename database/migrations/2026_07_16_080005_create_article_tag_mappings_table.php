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
        Schema::create('article_tag_mappings', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('article_id')
                  ->constrained('articles')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('tag_id')
                  ->constrained('article_tags')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->index('article_id');
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_tag_mappings');
    }
};
