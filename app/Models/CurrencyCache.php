<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CurrencyCache
 *
 * @package App\Models
 * @property int $id
 * @property string $base_currency
 * @property array $rates_data
 * @property Carbon $expires_at
 * @property Carbon|null $created_at
 */
class CurrencyCache extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'currency_cache';

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
        'base_currency',
        'rates_data',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rates_data' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Mutator & Accessor untuk mata uang basis (selalu uppercase).
     *
     * @return Attribute
     */
    protected function baseCurrency(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => strtoupper($value),
            set: fn (string $value): string => strtoupper(trim($value))
        );
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
     * Scope untuk menyaring cache berdasarkan mata uang basis.
     *
     * @param Builder $query
     * @param string $baseCurrency
     * @return Builder
     */
    public function scopeByCurrency(Builder $query, string $baseCurrency): Builder
    {
        return $query->where('base_currency', strtoupper(trim($baseCurrency)));
    }
}
