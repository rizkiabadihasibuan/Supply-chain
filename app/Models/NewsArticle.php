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
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class NewsArticle
 *
 * @package App\Models
 * @property int $id
 * @property int $country_id
 * @property int $source_id
 * @property int $category_id
 * @property string $title
 * @property string|null $description
 * @property string|null $content
 * @property string $url
 * @property string|null $image_url
 * @property string|null $author
 * @property Carbon|null $published_at
 * @property string $language
 * @property string $sentiment_status
 * @property float|null $risk_score_reference
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $formatted_publish_date
 * @property-read string|null $source_logo
 */
class NewsArticle extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'news_articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'source_id',
        'category_id',
        'title',
        'description',
        'content',
        'url',
        'image_url',
        'author',
        'published_at',
        'language',
        'sentiment_status',
        'risk_score_reference',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
        'risk_score_reference' => 'float',
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
     * Relasi BelongsTo ke NewsSource.
     *
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(NewsSource::class, 'source_id');
    }

    /**
     * Relasi BelongsTo ke NewsCategory.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    /**
     * Relasi One-to-One ke SentimentResult.
     *
     * @return HasOne
     */
    public function sentimentResult(): HasOne
    {
        return $this->hasOne(SentimentResult::class, 'news_article_id');
    }

    /**
     * Relasi One-to-Many ke RiskHistories (Future Ready).
     *
     * @return HasMany
     */
    public function riskHistories(): HasMany
    {
        return $this->hasMany(RiskHistory::class, 'country_id', 'country_id');
    }

    /**
     * Accessor untuk memformat tanggal rilis berita (misal: "18 Jul 2026 21:00").
     *
     * @return Attribute
     */
    protected function formattedPublishDate(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->published_at 
                ? $this->published_at->format('d M Y H:i') 
                : '-'
        );
    }

    /**
     * Accessor untuk mengambil logo portal berita terkait secara langsung.
     *
     * @return Attribute
     */
    protected function sourceLogo(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->source?->logo_url
        );
    }

    /**
     * Scope untuk mengurutkan berita terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope untuk memfilter berita berdasarkan negara.
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
     * Scope untuk memfilter berita berdasarkan kategori.
     *
     * @param Builder $query
     * @param int $categoryId
     * @return Builder
     */
    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope untuk memfilter berita berdasarkan media sumber berita.
     *
     * @param Builder $query
     * @param int $sourceId
     * @return Builder
     */
    public function scopeBySource(Builder $query, int $sourceId): Builder
    {
        return $query->where('source_id', $sourceId);
    }

    /**
     * Scope untuk melakukan pencarian berita berdasarkan judul/konten.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }
}
