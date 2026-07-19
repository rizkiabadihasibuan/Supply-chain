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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('category_id')
                  ->constrained('article_categories')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->string('title')->index();
            $table->string('slug')->unique()->index();
            $table->text('content');
            $table->string('thumbnail')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('status', 20)->default('draft')->index(); // draft, published
            $table->string('meta_description')->nullable(); // Future ready
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
