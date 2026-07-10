<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'code',
        'name',
        'address',
        'latitude',
        'longitude',
        'criticality',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_position',
        'documents',
        'performance_score',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    /**
     * Get the shipments associated with this supplier.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
