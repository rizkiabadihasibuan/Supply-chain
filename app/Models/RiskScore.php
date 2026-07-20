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

class RiskScore extends Model
{
    use HasFactory;

    protected $table = 'risk_scores';

    protected $fillable = [
        'country_id',
        'classification_id',
        'final_risk_score',
        'risk_level',
        'components',
        'history',
    ];

    protected $casts = [
        'final_risk_score' => 'float',
        'components' => 'array',
        'history' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(RiskClassification::class, 'classification_id');
    }

    // ── Virtual Accessors for Backward Compatibility ──────────────────
    public function getWeatherScoreAttribute(): float
    {
        return (float) ($this->components['weather'] ?? 0.0);
    }

    public function getInflationScoreAttribute(): float
    {
        return (float) ($this->components['inflation'] ?? 0.0);
    }

    public function getCurrencyScoreAttribute(): float
    {
        return (float) ($this->components['currency'] ?? 0.0);
    }

    public function getPoliticalScoreAttribute(): float
    {
        return (float) ($this->components['political'] ?? 0.0);
    }

    public function getEconomicScoreAttribute(): float
    {
        return (float) ($this->components['economic'] ?? 0.0);
    }

    public function getLogisticsScoreAttribute(): float
    {
        return (float) ($this->components['logistics'] ?? 0.0);
    }

    protected function formattedFinalScore(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format($this->final_risk_score, 1)
        );
    }

    protected function riskBadge(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('<span class="badge" style="background-color: %s;">%s</span>', $this->risk_color, $this->risk_level)
        );
    }

    protected function riskColor(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->classification?->color_code ?? '#6B7280'
        );
    }

    protected function riskPercentage(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%s%%', $this->formatted_final_score)
        );
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeHighest(Builder $query): Builder
    {
        return $query->orderBy('final_risk_score', 'desc');
    }

    public function scopeLowest(Builder $query): Builder
    {
        return $query->orderBy('final_risk_score', 'asc');
    }

    public function scopeCritical(Builder $query): Builder
    {
        return $query->where('final_risk_score', '>=', 4.00); // Skala 0-5
    }

    public function scopeByCountry(Builder $query, int $countryId): Builder
    {
        return $query->where('country_id', $countryId);
    }
}
