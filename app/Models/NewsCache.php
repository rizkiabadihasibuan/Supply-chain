<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'country_id', 
    'title', 
    'description', 
    'url', 
    'source_name', 
    'published_at', 
    'sentiment_label', 
    'positive_score', 
    'negative_score', 
    'fetched_at'
])]
class NewsCache extends Model
{
    protected $table = 'news_cache';

    /**
     * Get the country associated with this news cache.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
