<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskRule extends Model
{
    protected $fillable = [
        'risk_category_id',
        'rule_name',
        'condition_operator',
        'condition_value',
        'risk_impact_weight',
        'status',
    ];

    /**
     * Get the category of this rule.
     */
    public function riskCategory(): BelongsTo
    {
        return $this->belongsTo(RiskCategory::class);
    }
}
