<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class NotificationPreference
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property bool $email
 * @property bool $in_app
 * @property bool $risk_alert
 * @property bool $weather_alert
 * @property bool $currency_alert
 * @property bool $news_alert
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class NotificationPreference extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'notification_preferences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'email',
        'in_app',
        'risk_alert',
        'weather_alert',
        'currency_alert',
        'news_alert',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email' => 'boolean',
        'in_app' => 'boolean',
        'risk_alert' => 'boolean',
        'weather_alert' => 'boolean',
        'currency_alert' => 'boolean',
        'news_alert' => 'boolean',
    ];

    /**
     * Relasi BelongsTo ke User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
