<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeopoliticalEvent extends Model
{
    protected $fillable = [
        'event_source_id',
        'title',
        'content_summary',
        'country_code',
        'latitude',
        'longitude',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];
}
