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
        'subregion',
        'population',
        'area',
        'timezone',
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

    /**
     * Relasi Many-to-Many ke Languages.
     *
     * @return BelongsToMany
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'country_languages');
    }

    /**
     * Relasi One-to-One ke CountryCoordinates.
     *
     * @return HasOne
     */
    public function coordinates(): HasOne
    {
        return $this->hasOne(CountryCoordinate::class);
    }

    /**
     * Relasi One-to-One ke CountryFlags.
     *
     * @return HasOne
     */
    public function flag(): HasOne
    {
        return $this->hasOne(CountryFlag::class);
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

    /**
     * Relasi One-to-Many ke WeatherAlerts.
     *
     * @return HasMany
     */
    public function weatherAlerts(): HasMany
    {
        return $this->hasMany(WeatherAlert::class);
    }

    /**
     * Relasi One-to-Many ke WeatherHistories.
     *
     * @return HasMany
     */
    public function weatherHistories(): HasMany
    {
        return $this->hasMany(WeatherHistory::class);
    }

    /**
     * Relasi One-to-One ke RiskScore.
     *
     * @return HasOne
     */
    public function riskScore(): HasOne
    {
        return $this->hasOne(RiskScore::class);
    }

    /**
     * Relasi One-to-Many ke RiskSnapshots.
     *
     * @return HasMany
     */
    public function riskSnapshots(): HasMany
    {
        return $this->hasMany(RiskSnapshot::class);
    }

    /**
     * Relasi One-to-Many ke RiskAlerts.
     *
     * @return HasMany
     */
    public function riskAlerts(): HasMany
    {
        return $this->hasMany(RiskAlert::class);
    }

    /**
     * Relasi One-to-Many ke RiskTrends.
     *
     * @return HasMany
     */
    public function riskTrends(): HasMany
    {
        return $this->hasMany(RiskTrend::class);
    }

    /**
     * Relasi One-to-Many ke RiskHistories.
     *
     * @return HasMany
     */
    public function riskHistories(): HasMany
    {
        return $this->hasMany(RiskHistory::class);
    }

    /**
     * Relasi One-to-Many ke SentimentHistories.
     *
     * @return HasMany
     */
    public function sentimentHistories(): HasMany
    {
        return $this->hasMany(SentimentHistory::class);
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
            get: fn (): ?string => $this->flag?->flag_url
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
