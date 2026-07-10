<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * Get the shipments associated with this warehouse.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
