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
        Schema::create('risk_trends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->string('trend_type', 100);
            $table->decimal('previous_score', 5, 2)->default(0.00);
            $table->decimal('current_score', 5, 2)->default(0.00)->index();
            $table->decimal('change_percentage', 5, 2)->default(0.00);
            $table->string('trend_direction', 50)->default('Stable')->index(); // Up, Down, Stable
            $table->timestamp('analyzed_at')->useCurrent();
            $table->timestamps();

            $table->index('country_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_trends');
    }
};
