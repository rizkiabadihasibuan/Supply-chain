<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'title', 'slug', 'content', 'status'])]
class Article extends Model
{
    /**
     * Get the author of the article.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
