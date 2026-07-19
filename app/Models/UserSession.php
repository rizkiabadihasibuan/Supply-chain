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
 * Class UserSession
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string $session_id
 * @property string|null $ip_address
 * @property string|null $device
 * @property string|null $browser
 * @property string|null $platform
 * @property int $last_activity
 * @property Carbon|null $expired_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $session_duration
 */
class UserSession extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'user_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'device',
        'browser',
        'platform',
        'last_activity',
        'expired_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_activity' => 'integer',
        'expired_at' => 'datetime',
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

    /**
     * Accessor untuk mengukur durasi/selisih waktu aktivitas terakhir.
     *
     * @return Attribute
     */
    protected function sessionDuration(): Attribute
    {
        return Attribute::make(
            get: fn (): string => Carbon::createFromTimestamp($this->last_activity)->diffForHumans()
        );
    }

    /**
     * Scope untuk menyaring sesi aktif yang belum kedaluwarsa.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('expired_at')
              ->orWhere('expired_at', '>=', Carbon::now());
        });
    }

    /**
     * Scope untuk menyaring berdasarkan pengguna.
     *
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk mengurutkan sesi terupdate.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('last_activity', 'desc');
    }
}
