<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskAlert extends Model
{
    protected $fillable = [
        'shipment_id',
        'risk_category_id',
        'severity',
        'description',
        'status',
        'resolved_by_user_id',
        'resolution_action',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the shipment linked to this alert.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the risk category.
     */
    public function riskCategory(): BelongsTo
    {
        return $this->belongsTo(RiskCategory::class);
    }

    /**
     * Get the user who resolved this alert.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }
}
