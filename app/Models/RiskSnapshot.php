<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class RiskSnapshot
 *
 * @package App\Models
 * @property int $id
 * @property int $country_id
 * @property array|null $weather_data
 * @property array|null $economic_data
 * @property array|null $news_data
 * @property array|null $port_data
 * @property string $overall_status
 * @property Carbon $snapshot_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class RiskSnapshot extends Model
{
    use HasFactory;

    protected $table = 'risk_snapshots';

    protected $fillable = [
        'country_id',
        'weather_data',
        'economic_data',
        'news_data',
        'port_data',
        'overall_status',
        'snapshot_time',
    ];

    protected $casts = [
        'weather_data' => 'array',
        'economic_data' => 'array',
        'news_data' => 'array',
        'port_data' => 'array',
        'snapshot_time' => 'datetime',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function riskScore(): HasOne
    {
        return $this->hasOne(RiskScore::class, 'snapshot_id');
    }
}
