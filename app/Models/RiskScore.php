<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'country_id', 
    'weather_risk_score', 
    'inflation_risk_score', 
    'political_risk_score', 
    'currency_risk_score', 
    'total_risk_score', 
    'risk_level', 
    'calculated_at'
])]
class RiskScore extends Model
{
    /**
     * Get the country associated with this risk score.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
