<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'from_currency', 
    'to_currency', 
    'rate', 
    'volatility', 
    'last_updated_at'
])]
class CurrencyCache extends Model
{
    protected $table = 'currency_cache';
}
