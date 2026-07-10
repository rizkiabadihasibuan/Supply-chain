<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [
        'shipment_number',
        'supplier_id',
        'warehouse_id',
        'carrier_id',
        'departure_date',
        'estimated_delivery',
        'actual_delivery',
        'status',
        'current_risk_score',
        'route_points',
        'tracking_logs',
    ];

    protected $casts = [
        'route_points' => 'array',
        'tracking_logs' => 'array',
    ];

    /**
     * Get the supplier of this shipment.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the warehouse destination.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the logistics carrier.
     */
    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class);
    }

    /**
     * Get the risk alerts for this shipment.
     */
    public function riskAlerts(): HasMany
    {
        return $this->hasMany(RiskAlert::class);
    }
}
