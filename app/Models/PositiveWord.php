<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PositiveWord
 *
 * @package App\Models
 * @property int $id
 * @property int $dictionary_id
 * @property string $word
 * @property float $score
 * @property string $language
 * @property string|null $description
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PositiveWord extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'positive_words';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dictionary_id',
        'word',
        'score',
        'language',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'float',
        'status' => 'boolean',
    ];

    /**
     * Relasi BelongsTo ke SentimentDictionary.
     *
     * @return BelongsTo
     */
    public function dictionary(): BelongsTo
    {
        return $this->belongsTo(SentimentDictionary::class, 'dictionary_id');
    }

    /**
     * Mutator & Accessor kata (selalu lowercase, trim, encoding UTF-8 aman).
     *
     * @return Attribute
     */
    protected function word(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => mb_strtolower($value, 'UTF-8'),
            set: fn (string $value): string => mb_strtolower(trim($value), 'UTF-8')
        );
    }

    /**
     * Scope untuk menyaring kata positif yang aktif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * Scope untuk memfilter kata berdasarkan bahasa.
     *
     * @param Builder $query
     * @param string $language
     * @return Builder
     */
    public function scopeByLanguage(Builder $query, string $language): Builder
    {
        return $query->where('language', strtolower(trim($language)));
    }
}
