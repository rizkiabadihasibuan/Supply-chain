<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CurrencyHistory
 *
 * @package App\Models
 * @property int $id
 * @property int $currency_id
 * @property float $rate_vs_usd
 * @property Carbon $recorded_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class CurrencyHistory extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'currency_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'currency_id',
        'rate_vs_usd',
        'recorded_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rate_vs_usd' => 'float',
        'recorded_date' => 'date',
    ];

    /**
     * Relasi BelongsTo ke Currency.
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Scope untuk mengambil riwayat terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('recorded_date', 'desc');
    }

    /**
     * Scope untuk memfilter berdasarkan mata uang.
     *
     * @param Builder $query
     * @param int $currencyId
     * @return Builder
     */
    public function scopeByCurrency(Builder $query, int $currencyId): Builder
    {
        return $query->where('currency_id', $currencyId);
    }

    /**
     * Scope untuk memfilter data hari ini.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('recorded_date', Carbon::today());
    }

    /**
     * Accessor untuk memformat tampilan nilai tukar (misal: "1.000000").
     *
     * @return Attribute
     */
    protected function formattedExchangeRate(): Attribute
    {
        return Attribute::make(
            get: fn (): string => number_format($this->rate_vs_usd, 6)
        );
    }
}
