<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SentimentMatch
 *
 * @package App\Models
 * @property int $id
 * @property int $sentiment_result_id
 * @property string $matched_word
 * @property string $word_type
 * @property float $word_score
 * @property int $position
 * @property int $frequency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SentimentMatch extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'sentiment_matches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sentiment_result_id',
        'matched_word',
        'word_type',
        'word_score',
        'position',
        'frequency',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'word_score' => 'float',
        'position' => 'integer',
        'frequency' => 'integer',
    ];

    /**
     * Relasi BelongsTo ke SentimentResult.
     *
     * @return BelongsTo
     */
    public function sentimentResult(): BelongsTo
    {
        return $this->belongsTo(SentimentResult::class, 'sentiment_result_id');
    }
}
