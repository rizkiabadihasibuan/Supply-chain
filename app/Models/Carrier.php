<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrier extends Model
{
    protected $fillable = [
        'code',
        'name',
        'contact_email',
        'api_tracking_endpoint',
    ];

    /**
     * Get the shipments associated with this carrier.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
