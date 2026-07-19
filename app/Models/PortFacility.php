<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PortFacility
 *
 * @package App\Models
 * @property int $id
 * @property int $port_id
 * @property bool $dry_dock
 * @property bool $container_terminal
 * @property int|null $crane_capacity_tons
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PortFacility extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'port_facilities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'port_id',
        'dry_dock',
        'container_terminal',
        'crane_capacity_tons',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dry_dock' => 'boolean',
        'container_terminal' => 'boolean',
        'crane_capacity_tons' => 'integer',
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
}
