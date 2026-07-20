<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskTrend extends Model
{
    use HasFactory;

    protected $table = 'risk_trends';

    protected $fillable = [
        'country_id',
        'trend_type',
        'previous_score',
        'current_score',
        'change_percentage',
        'trend_direction',
        'analyzed_at',
    ];

    protected $casts = [
        'previous_score' => 'float',
        'current_score' => 'float',
        'change_percentage' => 'float',
        'analyzed_at' => 'datetime',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
