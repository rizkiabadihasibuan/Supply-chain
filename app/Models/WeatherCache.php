<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'country_id', 
    'temperature', 
    'rain', 
    'wind_speed', 
    'storm_risk', 
    'condition_code', 
    'checked_at'
])]
class WeatherCache extends Model
{
    protected $table = 'weather_cache';

    /**
     * Get the country associated with this weather cache.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
