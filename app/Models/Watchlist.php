<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'country_id'])]
class Watchlist extends Model
{
    /**
     * Get the user who owns this watchlist item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the country monitored in this watchlist item.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
