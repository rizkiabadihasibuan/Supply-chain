<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'api_name', 
    'endpoint', 
    'response_status', 
    'execution_time', 
    'requested_at'
])]
class ApiRequestLog extends Model
{
    //
}
