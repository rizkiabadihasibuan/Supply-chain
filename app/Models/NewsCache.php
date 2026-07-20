<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NewsCache
 *
 * @package App\Models
 * @property int $id
 * @property string $query
 * @property array $news_data
 * @property Carbon $expires_at
 * @property Carbon|null $created_at
 */
class NewsCache extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'news_cache';

    /**
     * Menonaktifkan updated_at timestamps bawaan Laravel.
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'query',
        'news_data',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'news_data' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Accessor untuk query berita (selalu uppercase untuk keseragaman indeks).
     */
    public function getQueryAttribute(string $value): string
    {
        return strtoupper($value);
    }

    /**
     * Mutator untuk query berita (selalu uppercase trim untuk keseragaman indeks).
     */
    public function setQueryAttribute(string $value): void
    {
        $this->attributes['query'] = strtoupper(trim($value));
    }

    /**
     * Scope untuk menyaring cache yang sudah kadaluwarsa.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<', Carbon::now());
    }

    /**
     * Scope untuk menyaring cache yang masih valid (aktif).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expires_at', '>=', Carbon::now());
    }

    /**
     * Scope untuk memfilter cache berdasarkan string query.
     *
     * @param Builder $query
     * @param string $queryString
     * @return Builder
     */
    public function scopeByQuery(Builder $query, string $queryString): Builder
    {
        return $query->where('query', strtoupper(trim($queryString)));
    }
}
