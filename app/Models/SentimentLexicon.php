<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentimentLexicon extends Model
{
    protected $table = 'sentiment_lexicon';

    protected $fillable = [
        'word',
        'type',
    ];
}
