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
 * Class AuditLog
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $module
 * @property array|null $old_value
 * @property array|null $new_value
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon|null $created_at
 * @property-read string $formatted_created_at
 */
class AuditLog extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'audit_logs';

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
        'user_id',
        'action',
        'module',
        'old_value',
        'new_value',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
        'created_at' => 'datetime',
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
     * Accessor untuk memformat waktu pembuatan audit secara human-friendly.
     *
     * @return Attribute
     */
    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->created_at 
                ? $this->created_at->format('d M Y H:i:s') 
                : '-'
        );
    }

    /**
     * Scope untuk mengurutkan log audit terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
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
     * Scope untuk menyaring berdasarkan modul.
     *
     * @param Builder $query
     * @param string $module
     * @return Builder
     */
    public function scopeByModule(Builder $query, string $module): Builder
    {
        return $query->where('module', strtolower(trim($module)));
    }

    /**
     * Scope untuk menyaring log hari ini.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', Carbon::today());
    }
}
