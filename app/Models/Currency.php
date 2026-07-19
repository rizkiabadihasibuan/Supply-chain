<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Currency
 *
 * @package App\Models
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $symbol
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $formatted_currency
 */
class Currency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
    ];

    /**
     * Relasi One-to-Many ke Countries.
     *
     * @return HasMany
     */
    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }

    /**
     * Relasi One-to-Many ke CurrencyHistories.
     *
     * @return HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(CurrencyHistory::class);
    }

    /**
     * Relasi One-to-Many ke CurrencyTrends.
     *
     * @return HasMany
     */
    public function trends(): HasMany
    {
        return $this->hasMany(CurrencyTrend::class);
    }

    /**
     * Mutator & Accessor untuk kode mata uang (selalu uppercase).
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
     * Accessor untuk memformat tampilan mata uang (misal: "USD ($)").
     *
     * @return Attribute
     */
    protected function formattedCurrency(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%s (%s)', $this->code, $this->symbol ?? '-')
        );
    }
}
