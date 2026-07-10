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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // ISO 2-letter or 3-letter code
            $table->string('name');
            $table->string('currency_code', 10)->nullable();
            $table->string('currency_name')->nullable();
            $table->string('region')->nullable();
            $table->string('language')->nullable();
            $table->bigInteger('gdp')->nullable(); // GDP in USD
            $table->decimal('inflation', 5, 2)->nullable(); // Inflation rate in %
            $table->bigInteger('population')->nullable();
            $table->decimal('current_weather_temp', 5, 2)->nullable();
            $table->string('current_weather_condition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
