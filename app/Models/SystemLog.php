<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemLog
 *
 * @package App\Models
 * @property int $id
 * @property string $level
 * @property string $module
 * @property string $message
 * @property array|null $context
 * @property Carbon|null $created_at
 * @property-read string $formatted_created_at
 * @property-read string $level_badge
 */
class SystemLog extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'system_logs';

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
        'level',
        'module',
        'message',
        'context',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'context' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Accessor untuk memformat waktu pembuatan log secara human-friendly.
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
     * Accessor untuk memetakan level log menjadi HTML badge Bootstrap.
     *
     * @return Attribute
     */
    protected function levelBadge(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match (strtolower($this->level)) {
                'emergency', 'alert', 'critical', 'error' => sprintf('<span class="badge bg-danger">%s</span>', strtoupper($this->level)),
                'warning' => sprintf('<span class="badge bg-warning text-dark">%s</span>', strtoupper($this->level)),
                default => sprintf('<span class="badge bg-info">%s</span>', strtoupper($this->level))
            }
        );
    }

    /**
     * Scope untuk mengurutkan log terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
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

    /**
     * Scope untuk memfilter berdasarkan level log.
     *
     * @param Builder $query
     * @param string $level
     * @return Builder
     */
    public function scopeByLevel(Builder $query, string $level): Builder
    {
        return $query->where('level', strtolower(trim($level)));
    }

    /**
     * Scope untuk memfilter berdasarkan modul sistem.
     *
     * @param Builder $query
     * @param string $module
     * @return Builder
     */
    public function scopeByModule(Builder $query, string $module): Builder
    {
        return $query->where('module', strtolower(trim($module)));
    }
}
