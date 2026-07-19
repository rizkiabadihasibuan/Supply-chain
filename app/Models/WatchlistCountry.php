<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class WatchlistCountry
 *
 * @package App\Models
 * @property int $id
 * @property int $watchlist_id
 * @property int $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class WatchlistCountry extends Pivot
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'watchlist_countries';

    /**
     * Mengatur agar model pivot mengenali auto-increment key id.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'watchlist_id',
        'country_id',
    ];

    /**
     * Relasi BelongsTo ke Watchlist.
     *
     * @return BelongsTo
     */
    public function watchlist(): BelongsTo
    {
        return $this->belongsTo(Watchlist::class, 'watchlist_id');
    }

    /**
     * Relasi BelongsTo ke Country.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
