<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SentimentDictionary
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $lang_code
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SentimentDictionary extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'sentiment_dictionaries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lang_code',
        'description',
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
     * Relasi One-to-Many ke PositiveWords.
     *
     * @return HasMany
     */
    public function positiveWords(): HasMany
    {
        return $this->hasMany(PositiveWord::class, 'dictionary_id');
    }

    /**
     * Relasi One-to-Many ke NegativeWords.
     *
     * @return HasMany
     */
    public function negativeWords(): HasMany
    {
        return $this->hasMany(NegativeWord::class, 'dictionary_id');
    }

    /**
     * Scope untuk mengambil kamus sentimen aktif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
