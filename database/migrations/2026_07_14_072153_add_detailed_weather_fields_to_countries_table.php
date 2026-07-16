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
        Schema::table('countries', function (Blueprint $table) {
            $table->integer('current_weather_humidity')->nullable()->after('current_weather_condition');
            $table->integer('current_weather_wind_direction')->nullable()->after('current_weather_wind_speed');
            $table->decimal('current_weather_rain', 5, 2)->nullable()->after('current_weather_precipitation');
            $table->integer('current_weather_code')->nullable()->after('current_weather_storm_risk');
            $table->json('weather_forecast_7_days')->nullable()->after('current_weather_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn([
                'current_weather_humidity',
                'current_weather_wind_direction',
                'current_weather_rain',
                'current_weather_code',
                'weather_forecast_7_days',
            ]);
        });
    }
};
