<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Article
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string|null $thumbnail
 * @property Carbon|null $published_at
 * @property string $status
 * @property string|null $meta_description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $formatted_publish_date
 * @property-read int $reading_time
 * @property-read string $thumbnail_url
 * @property-read string $article_status_badge
 */
class Article extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'thumbnail',
        'published_at',
        'status',
        'meta_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Relasi BelongsTo ke User (Penulis).
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi BelongsTo ke ArticleCategory.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * Relasi Many-to-Many ke ArticleTags (via pivot article_tag_mappings).
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ArticleTag::class, 'article_tag_mappings', 'article_id', 'tag_id');
    }

    /**
     * Mutator untuk slug otomatis dari title jika tidak dideklarasikan.
     *
     * @return Attribute
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): string => Str::slug($value)
        );
    }

    /**
     * Accessor untuk memformat tanggal rilis artikel (misal: "18 Jul 2026").
     *
     * @return Attribute
     */
    protected function formattedPublishDate(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->published_at 
                ? $this->published_at->format('d M Y') 
                : '-'
        );
    }

    /**
     * Accessor untuk menghitung estimasi waktu membaca (WPM: 200).
     *
     * @return Attribute
     */
    protected function readingTime(): Attribute
    {
        return Attribute::make(
            get: fn (): int => max(1, (int) ceil(str_word_count(strip_tags($this->content)) / 200))
        );
    }

    /**
     * Accessor untuk mengambil thumbnail URL atau fallback default aset.
     *
     * @return Attribute
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->thumbnail 
                ? asset($this->thumbnail) 
                : asset('images/default-article.jpg')
        );
    }

    /**
     * Accessor untuk memetakan kelas warna Bootstrap badge status artikel.
     *
     * @return Attribute
     */
    protected function articleStatusBadge(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->status === 'published' 
                ? '<span class="badge bg-success">Published</span>' 
                : '<span class="badge bg-secondary">Draft</span>'
        );
    }

    /**
     * Scope untuk memfilter artikel terpublikasi.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', Carbon::now());
    }

    /**
     * Scope untuk memfilter draf artikel.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope untuk mengurutkan laporan terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope untuk memfilter berdasarkan kategori artikel.
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
     * Scope untuk mencari judul atau konten artikel.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('content', 'like', '%' . $search . '%');
        });
    }
}
