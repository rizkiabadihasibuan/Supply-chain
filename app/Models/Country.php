<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Country
 *
 * @package App\Models
 * @property int $id
 * @property int $region_id
 * @property int $currency_id
 * @property string $code
 * @property string $name
 * @property string|null $subregion
 * @property int|null $population
 * @property float|null $area
 * @property string|null $timezone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $country_flag_url
 */
class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'region_id',
        'currency_id',
        'code',
        'name',
        'capital',
        'subregion',
        'population',
        'area',
        'timezone',
        'latitude',
        'longitude',
        'flag_url',
        'languages',
    ];

    protected $casts = [
        'languages' => 'array',
    ];

    /**
     * Relasi BelongsTo ke Region.
     *
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Relasi BelongsTo ke Currency.
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function coordinates()
    {
        return (object)[
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }

    public function getCoordinatesAttribute()
    {
        return $this->coordinates();
    }

    /**
     * Virtual flag attribute to mirror old flag relationship.
     */
    public function getFlagAttribute()
    {
        return (object)[
            'flag_url' => $this->flag_url,
            'file_url' => $this->flag_url
        ];
    }

    /**
     * Virtual languages attribute to mirror old languages relationship.
     */
    public function getLanguagesAttribute()
    {
        $langs = $this->attributes['languages'] ?? '[]';
        $decoded = is_string($langs) ? json_decode($langs, true) : $langs;
        $decoded = is_array($decoded) ? $decoded : [];
        return collect($decoded)->map(function ($langName) {
            return (object)['name' => $langName];
        });
    }

    /**
     * Relasi One-to-Many ke Ports.
     *
     * @return HasMany
     */
    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }

    public function weatherHistories(): HasMany
    {
        return $this->hasMany(WeatherCache::class);
    }

    public function riskScore(): HasOne
    {
        return $this->hasOne(RiskScore::class);
    }

    public function riskAlerts(): HasMany
    {
        return $this->hasMany(RiskAlert::class);
    }

    /**
     * Relasi One-to-Many ke NewsArticles.
     *
     * @return HasMany
     */
    public function newsArticles(): HasMany
    {
        return $this->hasMany(NewsArticle::class);
    }

    /**
     * Relasi Many-to-Many ke Watchlists (via pivot watchlist_countries).
     *
     * @return BelongsToMany
     */
    public function watchlists(): BelongsToMany
    {
        return $this->belongsToMany(Watchlist::class, 'watchlist_countries');
    }

    /**
     * Mutator & Accessor untuk kode ISO 2-karakter (selalu uppercase).
     *
     * @return Attribute
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => strtoupper($value),
            set: fn (string $value): string => strtoupper(trim($value))
        );
    }

    /**
     * Accessor untuk mengambil URL bendera secara langsung dari relasi flag.
     *
     * @return Attribute
     */
    protected function countryFlagUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->flag_url
        );
    }

    /**
     * Scope untuk menyaring negara berdasarkan Benua/Region.
     *
     * @param Builder $query
     * @param int $regionId
     * @return Builder
     */
    public function scopeByRegion(Builder $query, int $regionId): Builder
    {
        return $query->where('region_id', $regionId);
    }

    /**
     * Scope untuk menyaring negara berdasarkan nama.
     *
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    /**
     * Scope untuk menyaring negara berdasarkan kode ISO.
     *
     * @param Builder $query
     * @param string $code
     * @return Builder
     */
    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', strtoupper(trim($code)));
    }
}
