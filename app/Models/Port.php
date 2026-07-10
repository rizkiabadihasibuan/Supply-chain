<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'country_id', 
    'port_code', 
    'name', 
    'latitude', 
    'longitude', 
    'waiting_time_hours', 
    'congestion_rate'
])]
class Port extends Model
{
    /**
     * Get the country associated with this port.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
