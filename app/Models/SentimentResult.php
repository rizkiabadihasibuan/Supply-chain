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
 * Class SentimentResult
 *
 * @package App\Models
 * @property int $id
 * @property int $news_article_id
 * @property int $country_id
 * @property float $positive_score
 * @property float $negative_score
 * @property float $neutral_score
 * @property float $total_score
 * @property string $sentiment_label
 * @property float $confidence
 * @property Carbon|null $processed_at
 * @property string $analysis_version
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $formatted_score
 * @property-read string $sentiment_badge
 * @property-read string $sentiment_color
 * @property-read string $confidence_percentage
 * @property-read int $matched_word_count
 */
class SentimentResult extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'sentiment_results';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'news_article_id',
        'country_id',
        'positive_score',
        'negative_score',
        'neutral_score',
        'total_score',
        'sentiment_label',
        'confidence',
        'processed_at',
        'analysis_version',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'positive_score' => 'float',
        'negative_score' => 'float',
        'neutral_score' => 'float',
        'total_score' => 'float',
        'confidence' => 'float',
        'processed_at' => 'datetime',
    ];

    /**
     * Relasi BelongsTo ke NewsArticle.
     *
     * @return BelongsTo
     */
    public function newsArticle(): BelongsTo
    {
        return $this->belongsTo(NewsArticle::class, 'news_article_id');
    }

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
     * Relasi One-to-Many ke SentimentMatches.
     *
     * @return HasMany
     */
    public function matches(): HasMany
    {
        return $this->hasMany(SentimentMatch::class, 'sentiment_result_id');
    }

    /**
     * Relasi One-to-Many ke SentimentHistories.
     *
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(SentimentHistory::class, 'sentiment_result_id');
    }

    /**
     * Relasi BelongsTo ke RiskScore (Future Ready).
     *
     * @return BelongsTo
     */
    public function riskScore(): BelongsTo
    {
        return $this->belongsTo(RiskScore::class, 'country_id', 'country_id');
    }

    /**
     * Accessor untuk memformat total skor NLP sentimen (misal: "+1.2").
     *
     * @return Attribute
     */
    protected function formattedScore(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%s%s', $this->total_score > 0 ? '+' : '', number_format($this->total_score, 1))
        );
    }

    /**
     * Accessor untuk memetakan HTML badge Bootstrap level sentimen.
     *
     * @return Attribute
     */
    protected function sentimentBadge(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('<span class="badge" style="background-color: %s;">%s</span>', $this->sentiment_color, ucfirst($this->sentiment_label))
        );
    }

    /**
     * Accessor untuk memetakan warna latar belakang sentimen.
     *
     * @return Attribute
     */
    protected function sentimentColor(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match (strtolower($this->sentiment_label)) {
                'positive' => '#10B981', // Emerald Green
                'negative' => '#EF4444', // Red
                default => '#6B7280' // Gray
            }
        );
    }

    /**
     * Accessor untuk memformat persentase akurasi NLP (misal: "95.0%").
     *
     * @return Attribute
     */
    protected function confidencePercentage(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%s%%', number_format($this->confidence * 100, 1))
        );
    }

    /**
     * Accessor untuk mendapatkan jumlah total kata sentimen yang cocok.
     *
     * @return Attribute
     */
    protected function matchedWordCount(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->matches()->count()
        );
    }

    /**
     * Scope untuk menyaring sentimen positif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePositive(Builder $query): Builder
    {
        return $query->where('sentiment_label', 'positive');
    }

    /**
     * Scope untuk menyaring sentimen negatif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNegative(Builder $query): Builder
    {
        return $query->where('sentiment_label', 'negative');
    }

    /**
     * Scope untuk menyaring sentimen netral.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNeutral(Builder $query): Builder
    {
        return $query->where('sentiment_label', 'neutral');
    }

    /**
     * Scope untuk mengurutkan analisis terupdate.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('processed_at', 'desc');
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
     * Scope untuk menyaring data yang sudah terproses.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeProcessed(Builder $query): Builder
    {
        return $query->whereNotNull('processed_at');
    }

    /**
     * Scope untuk menyaring data hari ini.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('processed_at', Carbon::today());
    }
}
