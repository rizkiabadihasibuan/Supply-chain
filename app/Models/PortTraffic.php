<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PortTraffic
 *
 * @package App\Models
 * @property int $id
 * @property int $port_id
 * @property int $vessel_count
 * @property float $avg_waiting_time_hours
 * @property Carbon $recorded_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PortTraffic extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'port_traffic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'port_id',
        'vessel_count',
        'avg_waiting_time_hours',
        'recorded_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vessel_count' => 'integer',
        'avg_waiting_time_hours' => 'float',
        'recorded_date' => 'date',
    ];

    /**
     * Relasi BelongsTo ke Port.
     *
     * @return BelongsTo
     */
    public function port(): BelongsTo
    {
        return $this->belongsTo(Port::class, 'port_id');
    }

    /**
     * Scope untuk mengurutkan traffic terupdate.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('recorded_date', 'desc');
    }
}
