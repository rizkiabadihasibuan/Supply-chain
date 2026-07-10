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
        Schema::create('weather_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->decimal('temperature', 5, 2);
            $table->decimal('rain', 5, 2)->default(0.00); // Rain in mm
            $table->decimal('wind_speed', 5, 2)->default(0.00); // Wind speed in km/h
            $table->decimal('storm_risk', 5, 2)->default(0.00); // Simulated or calculated risk
            $table->integer('condition_code')->nullable(); // Open-Meteo code
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_cache');
    }
};
