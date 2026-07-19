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
        Schema::create('risk_weights', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('category_id')
                  ->constrained('risk_categories')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->decimal('weight', 4, 2)->default(0.25);
            $table->date('effective_date')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_weights');
    }
};
