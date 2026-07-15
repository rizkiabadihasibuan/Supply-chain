<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code', 
    'name', 
    'iso2',
    'iso3',
    'flag_url',
    'currency_code', 
    'currency_name', 
    'currency_symbol',
    'region', 
    'subregion',
    'capital',
    'language', 
    'latitude',
    'longitude',
    'timezone',
    'gdp', 
    'inflation', 
    'population', 
    'area',
    'export_value',
    'import_value',
    'current_weather_temp', 
    'current_weather_condition',
    'current_weather_wind_speed',
    'current_weather_precipitation',
    'current_weather_storm_risk',
    'current_weather_humidity',
    'current_weather_wind_direction',
    'current_weather_rain',
    'current_weather_code',
    'weather_forecast_7_days',
    'exchange_rate',
    'exchange_rate_base',
    'exchange_rate_history',
])]
class Country extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weather_forecast_7_days' => 'array',
        'exchange_rate_history' => 'array',
    ];
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
