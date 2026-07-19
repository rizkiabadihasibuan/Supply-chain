<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Port
 *
 * @package App\Models
 * @property int $id
 * @property int $country_id
 * @property int $category_id
 * @property string $code
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 * @property string|null $size
 * @property string|null $type
 * @property string|null $harbor_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Port extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'category_id',
        'code',
        'name',
        'latitude',
        'longitude',
        'size',
        'type',
        'harbor_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
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
     * Relasi BelongsTo ke PortCategory.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PortCategory::class, 'category_id');
    }

    /**
     * Relasi One-to-One ke PortFacilities.
     *
     * @return HasOne
     */
    public function facilities(): HasOne
    {
        return $this->hasOne(PortFacility::class);
    }

    /**
     * Relasi One-to-Many ke PortTraffic.
     *
     * @return HasMany
     */
    public function traffic(): HasMany
    {
        return $this->hasMany(PortTraffic::class);
    }

    /**
     * Mutator & Accessor untuk kode pelabuhan (selalu uppercase).
     *
     * @return Attribute
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => strtoupper($value),
            set: fn (string $value): string => strtoupper(trim($value))
        );
    }

    /**
     * Scope untuk menyaring pelabuhan berdasarkan negara.
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
     * Scope untuk menyaring pelabuhan berdasarkan kategori.
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
     * Scope untuk menyaring pelabuhan berdasarkan nama.
     *
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    /**
     * Scope untuk menyaring pelabuhan berdasarkan ukuran kapasitas.
     *
     * @param Builder $query
     * @param string $size
     * @return Builder
     */
    public function scopeBySize(Builder $query, string $size): Builder
    {
        return $query->where('size', $size);
    }
}
