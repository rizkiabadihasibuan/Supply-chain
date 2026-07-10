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
    'gdp', 
    'inflation', 
    'population', 
    'current_weather_temp', 
    'current_weather_condition'
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

    /**
     * Get the watchlists monitoring this country.
     */
    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * Get the news caches for this country.
     */
    public function newsCaches(): HasMany
    {
        return $this->hasMany(NewsCache::class);
    }

    /**
     * Get the weather caches for this country.
     */
    public function weatherCaches(): HasMany
    {
        return $this->hasMany(WeatherCache::class);
    }
}
