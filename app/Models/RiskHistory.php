<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RiskHistory
 *
 * @package App\Models
 * @property int $id
 * @property int $country_id
 * @property int $risk_score_id
 * @property float $total_risk_score
 * @property string $risk_level
 * @property Carbon $calculated_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class RiskHistory extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'risk_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'risk_score_id',
        'total_risk_score',
        'overall_score',
        'risk_level',
        'calculated_date',
        'recorded_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_risk_score' => 'float',
        'overall_score' => 'float',
        'calculated_date' => 'date',
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
     * Relasi BelongsTo ke RiskScore.
     *
     * @return BelongsTo
     */
    public function riskScore(): BelongsTo
    {
        return $this->belongsTo(RiskScore::class, 'risk_score_id');
    }

    /**
     * Scope untuk mengurutkan histori terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('calculated_date', 'desc');
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
     * Scope untuk memfilter berdasarkan skor.
     *
     * @param Builder $query
     * @param float $score
     * @return Builder
     */
    public function scopeByScore(Builder $query, float $score): Builder
    {
        return $query->where('total_risk_score', $score);
    }

    /**
     * Scope untuk menyaring data hari ini.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('calculated_date', Carbon::today());
    }

    /**
     * Scope untuk menyaring data minggu ini.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->whereBetween('calculated_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    }

    /**
     * Scope untuk menyaring data bulan ini.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('calculated_date', Carbon::now()->month)
                     ->whereYear('calculated_date', Carbon::now()->year);
    }
}
