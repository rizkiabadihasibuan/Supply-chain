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
        Schema::create('weather_histories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->decimal('temperature', 4, 2)->default(0.00);
            $table->decimal('wind_speed', 5, 2)->default(0.00);
            $table->integer('humidity')->default(0);
            $table->timestamp('recorded_at')->index();
            $table->timestamps();

            // Explicit index
            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_histories');
    }
};
