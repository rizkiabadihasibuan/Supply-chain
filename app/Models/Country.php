<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code', 
    'name', 
    'currency_code', 
    'currency_name', 
    'region', 
    'language', 
    'latitude',
    'longitude',
    'gdp', 
    'inflation', 
    'population', 
    'export_value',
    'import_value',
    'current_weather_temp', 
    'current_weather_condition',
    'current_weather_wind_speed',
    'current_weather_precipitation',
    'current_weather_storm_risk',
])]
class Country extends Model
{
    /**
     * Get the ports in this country.
     */
    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }

    /**
     * Get the risk scores for this country.
     */
    public function riskScores(): HasMany
    {
        return $this->hasMany(RiskScore::class);
    }
}
