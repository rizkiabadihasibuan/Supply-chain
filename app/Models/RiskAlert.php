<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RiskAlert
 *
 * @package App\Models
 * @property int $id
 * @property int $country_id
 * @property int $risk_score_id
 * @property string $alert_type
 * @property string $severity
 * @property string $title
 * @property string|null $description
 * @property string $status
 * @property Carbon|null $resolved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class RiskAlert extends Model
{
    use HasFactory;

    protected $table = 'risk_alerts';

    protected $fillable = [
        'country_id',
        'risk_score_id',
        'alert_type',
        'severity',
        'title',
        'description',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function riskScore(): BelongsTo
    {
        return $this->belongsTo(RiskScore::class, 'risk_score_id');
    }
}
