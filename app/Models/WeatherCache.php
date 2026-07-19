<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WeatherCache
 *
 * @package App\Models
 * @property int $id
 * @property float $latitude
 * @property float $longitude
 * @property array $weather_data
 * @property Carbon $expires_at
 * @property Carbon|null $created_at
 * @property-read string $formatted_temperature
 * @property-read bool $is_expired
 */
class WeatherCache extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'weather_cache';

    /**
     * Menonaktifkan updated_at timestamps bawaan Laravel.
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'latitude',
        'longitude',
        'weather_data',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'weather_data' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Scope untuk menyaring cache yang sudah kedaluwarsa.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<', Carbon::now());
    }

    /**
     * Scope untuk menyaring cache yang masih valid (aktif).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expires_at', '>=', Carbon::now());
    }

    /**
     * Accessor untuk mengecek status kadaluwarsa cache secara dinamis.
     *
     * @return Attribute
     */
    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->expires_at->isPast()
        );
    }

    /**
     * Accessor untuk memformat temperatur (membaca suhu saat ini dari array JSON).
     *
     * @return Attribute
     */
    protected function formattedTemperature(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%s°C', $this->weather_data['current']['temperature_2m'] ?? '0.0')
        );
    }
}
