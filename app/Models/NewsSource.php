<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class NewsSource
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string|null $logo_url
 * @property string|null $country_code
 * @property string|null $language_code
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class NewsSource extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'news_sources';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'logo_url',
        'country_code',
        'language_code',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi One-to-Many ke NewsArticles.
     *
     * @return HasMany
     */
    public function newsArticles(): HasMany
    {
        return $this->hasMany(NewsArticle::class, 'source_id');
    }

    /**
     * Mutator & Accessor untuk normalisasi URL (lowercase trim).
     *
     * @return Attribute
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => strtolower($value),
            set: fn (string $value): string => strtolower(trim($value))
        );
    }

    /**
     * Scope untuk menyaring portal berita yang aktif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
