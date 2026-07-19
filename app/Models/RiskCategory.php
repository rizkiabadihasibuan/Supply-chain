<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class RiskCategory
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class RiskCategory extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'risk_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi One-to-Many ke RiskComponents.
     *
     * @return HasMany
     */
    public function components(): HasMany
    {
        return $this->hasMany(RiskComponent::class, 'category_id');
    }

    /**
     * Relasi One-to-Many ke RiskWeights.
     *
     * @return HasMany
     */
    public function weights(): HasMany
    {
        return $this->hasMany(RiskWeight::class, 'category_id');
    }
}
