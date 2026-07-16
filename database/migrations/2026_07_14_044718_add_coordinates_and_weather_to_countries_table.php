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
            $table->decimal('latitude', 10, 8)->nullable()->after('language');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->decimal('current_weather_wind_speed', 5, 2)->nullable()->after('current_weather_condition');
            $table->decimal('current_weather_precipitation', 5, 2)->nullable()->after('current_weather_wind_speed');
            $table->decimal('current_weather_storm_risk', 5, 2)->nullable()->after('current_weather_precipitation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'current_weather_wind_speed',
                'current_weather_precipitation',
                'current_weather_storm_risk',
            ]);
        });
    }
};
