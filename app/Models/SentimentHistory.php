<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SentimentHistory
 *
 * @package App\Models
 * @property int $id
 * @property int $country_id
 * @property int $sentiment_result_id
 * @property float $avg_sentiment_score
 * @property Carbon $recorded_date
 * @property string|null $analysis_version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class SentimentHistory extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'sentiment_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'sentiment_result_id',
        'avg_sentiment_score',
        'recorded_date',
        'analysis_version',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'avg_sentiment_score' => 'float',
        'recorded_date' => 'date',
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
     * Relasi BelongsTo ke SentimentResult.
     *
     * @return BelongsTo
     */
    public function sentimentResult(): BelongsTo
    {
        return $this->belongsTo(SentimentResult::class, 'sentiment_result_id');
    }

    /**
     * Scope untuk menyaring histori terupdate.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('recorded_date', 'desc');
    }

    /**
     * Scope untuk menyaring histori berdasarkan negara.
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
     * Scope untuk menyaring histori harian.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('recorded_date', Carbon::today());
    }
}
