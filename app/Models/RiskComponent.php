<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RiskComponent
 *
 * @package App\Models
 * @property int $id
 * @property int $risk_score_id
 * @property int $category_id
 * @property string $indicator_name
 * @property float $raw_value
 * @property float $weight
 * @property float $weighted_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class RiskComponent extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'risk_components';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'risk_score_id',
        'category_id',
        'indicator_name',
        'raw_value',
        'weight',
        'weighted_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'raw_value' => 'float',
        'weight' => 'float',
        'weighted_score' => 'float',
    ];

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
     * Relasi BelongsTo ke RiskCategory.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(RiskCategory::class, 'category_id');
    }
}
