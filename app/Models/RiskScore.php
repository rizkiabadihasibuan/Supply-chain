<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class RiskScore
 *
 * @package App\Models
 * @property int $id
 * @property int $country_id
 * @property int $classification_id
 * @property float $weather_score
 * @property float $inflation_score
 * @property float $currency_score
 * @property float $political_score
 * @property float $final_risk_score
 * @property string $risk_level
 * @property Carbon|null $calculated_at
 * @property string|null $source_version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $formatted_final_score
 * @property-read string $risk_badge
 * @property-read string $risk_color
 * @property-read string $risk_percentage
 */
class RiskScore extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'risk_scores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'classification_id',
        'snapshot_id',
        'weather_score',
        'inflation_score',
        'currency_score',
        'political_score',
        'economic_score',
        'logistics_score',
        'overall_score',
        'final_risk_score',
        'risk_level',
        'calculated_at',
        'source_version',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weather_score' => 'float',
        'inflation_score' => 'float',
        'currency_score' => 'float',
        'political_score' => 'float',
        'economic_score' => 'float',
        'logistics_score' => 'float',
        'overall_score' => 'float',
        'final_risk_score' => 'float',
        'calculated_at' => 'datetime',
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
     * Relasi BelongsTo ke RiskSnapshot.
     *
     * @return BelongsTo
     */
    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(RiskSnapshot::class, 'snapshot_id');
    }

    /**
     * Relasi BelongsTo ke RiskClassification.
     *
     * @return BelongsTo
     */
    public function classification(): BelongsTo
    {
        return $this->belongsTo(RiskClassification::class, 'classification_id');
    }

    /**
     * Relasi One-to-Many ke RiskHistories.
     *
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(RiskHistory::class, 'risk_score_id');
    }

    /**
     * Relasi One-to-Many ke SentimentResults (Future Ready).
     *
     * @return HasMany
     */
    public function sentimentResults(): HasMany
    {
        return $this->hasMany(SentimentResult::class, 'country_id', 'country_id');
    }

    /**
     * Accessor untuk memformat tampilan skor akhir (misal: "72.5").
     *
     * @return Attribute
     */
    protected function formattedFinalScore(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format($this->final_risk_score, 1)
        );
    }

    /**
     * Accessor untuk memetakan HTML badge Bootstrap level risiko.
     *
     * @return Attribute
     */
    protected function riskBadge(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match (strtolower($this->risk_level)) {
                'very low', 'low' => sprintf('<span class="badge" style="background-color: %s;">%s</span>', $this->risk_color, $this->risk_level),
                'medium' => sprintf('<span class="badge text-dark" style="background-color: %s;">%s</span>', $this->risk_color, $this->risk_level),
                default => sprintf('<span class="badge" style="background-color: %s;">%s</span>', $this->risk_color, $this->risk_level),
            }
        );
    }

    /**
     * Accessor untuk mengambil kode warna representatif secara langsung.
     *
     * @return Attribute
     */
    protected function riskColor(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->classification?->color_code ?? '#6B7280'
        );
    }

    /**
     * Accessor untuk menambahkan symbol persen ke nilai risiko (misal: "72.5%").
     *
     * @return Attribute
     */
    protected function riskPercentage(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%s%%', $this->formatted_final_score)
        );
    }

    /**
     * Scope untuk mengurutkan data kalkulasi terupdate.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('calculated_at', 'desc');
    }

    /**
     * Scope untuk mengurutkan risiko tertinggi.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeHighest(Builder $query): Builder
    {
        return $query->orderBy('final_risk_score', 'desc');
    }

    /**
     * Scope untuk mengurutkan risiko terendah.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLowest(Builder $query): Builder
    {
        return $query->orderBy('final_risk_score', 'asc');
    }

    /**
     * Scope untuk memfilter level risiko kritis (kategori merah/bahaya).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCritical(Builder $query): Builder
    {
        return $query->where('final_risk_score', '>=', 75.00);
    }

    /**
     * Scope untuk menyaring berdasarkan negara.
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
     * Scope untuk menyaring berdasarkan klasifikasi risiko.
     *
     * @param Builder $query
     * @param int $classificationId
     * @return Builder
     */
    public function scopeByClassification(Builder $query, int $classificationId): Builder
    {
        return $query->where('classification_id', $classificationId);
    }
}
