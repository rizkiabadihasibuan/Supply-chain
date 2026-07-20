<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $table = 'api_logs';

    protected $fillable = [
        'log_type',
        'service_name',
        'api_name', // mapped virtual
        'endpoint',
        'method',
        'status_code',
        'is_success',
        'error_message',
        'duration_ms',
    ];

    protected $casts = [
        'is_success' => 'boolean',
        'duration_ms' => 'integer',
        'status_code' => 'integer',
    ];

    // ── Virtual Attribute for Backward Compatibility ──────────────────
    public function getApiNameAttribute()
    {
        return $this->attributes['service_name'] ?? '';
    }

    public function setApiNameAttribute($value)
    {
        $this->attributes['service_name'] = $value;
    }

    protected function formattedResponseTime(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%d ms', $this->duration_ms)
        );
    }

    public function scopeSuccess(Builder $query): Builder
    {
        return $query->where('is_success', true);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('is_success', false);
    }

    public function scopeByService(Builder $query, string $service): Builder
    {
        return $query->where('service_name', $service);
    }
}
