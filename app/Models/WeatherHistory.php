<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WeatherHistory
 *
 * @package App\Models
 * @property int $id
 * @property int $country_id
 * @property float $temperature
 * @property float $wind_speed
 * @property int $humidity
 * @property Carbon $recorded_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class WeatherHistory extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'weather_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'temperature',
        'wind_speed',
        'humidity',
        'recorded_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'temperature' => 'float',
        'wind_speed' => 'float',
        'humidity' => 'integer',
        'recorded_at' => 'datetime',
    ];

    /**
     * Relasi BelongsTo ke Country.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Scope untuk mengambil riwayat terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('recorded_at', 'desc');
    }

    /**
     * Scope untuk memfilter berdasarkan negara.
     *
     * @param Builder $query
     * @param int $countryId
     * @return Builder
     */
    public function scopeByCountry(Builder $query, int $countryId): Builder
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * Scope untuk memfilter data hari ini.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('recorded_at', Carbon::today());
    }

    /**
     * Accessor untuk memformat tampilan temperatur (misal: "27.5°C").
     *
     * @return Attribute
     */
    protected function formattedTemperature(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%s°C', number_format($this->temperature, 1))
        );
    }
}
