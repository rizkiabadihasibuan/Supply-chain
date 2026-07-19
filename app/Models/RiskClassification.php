<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class RiskClassification
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property float $min_score
 * @property float $max_score
 * @property string $color_code
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class RiskClassification extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'risk_classifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'min_score',
        'max_score',
        'color_code',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'min_score' => 'float',
        'max_score' => 'float',
    ];

    /**
     * Relasi One-to-Many ke RiskScores.
     *
     * @return HasMany
     */
    public function riskScores(): HasMany
    {
        return $this->hasMany(RiskScore::class, 'classification_id');
    }
}
