<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the risk rules for this category.
     */
    public function riskRules(): HasMany
    {
        return $this->hasMany(RiskRule::class);
    }
}
