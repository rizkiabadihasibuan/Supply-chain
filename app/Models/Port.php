<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Port extends Model
{
    use HasFactory;

    protected $table = 'ports';

    protected $fillable = [
        'country_id',
        'code',
        'name',
        'latitude',
        'longitude',
        'size',
        'type',
        'harbor_type',
        'facilities',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'facilities' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    // ── Virtual Property for Backward Compatibility ──────────────────
    public function getFacilitiesAttribute()
    {
        $fac = $this->attributes['facilities'] ?? '[]';
        $decoded = is_string($fac) ? json_decode($fac, true) : $fac;
        return (object)(is_array($decoded) ? $decoded : []);
    }

    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => strtoupper($value),
            set: fn (string $value): string => strtoupper(trim($value))
        );
    }

    public function scopeByCountry(Builder $query, int $countryId): Builder
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeBySize(Builder $query, string $size): Builder
    {
        return $query->where('size', $size);
    }
}
