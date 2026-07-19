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
 * Class Notification
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * @property string $type
 * @property string $priority
 * @property bool $is_read
 * @property Carbon|null $read_at
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $notification_badge
 * @property-read string $priority_label
 * @property-read string $formatted_created_at
 */
class Notification extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'priority',
        'is_read',
        'read_at',
        'reference_type',
        'reference_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
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
     * Accessor untuk memetakan HTML badge Bootstrap tipe notifikasi.
     *
     * @return Attribute
     */
    protected function notificationBadge(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match (strtolower($this->type)) {
                'danger', 'error', 'critical' => '<span class="badge bg-danger">Critical</span>',
                'warning' => '<span class="badge bg-warning text-dark">Warning</span>',
                'success' => '<span class="badge bg-success">Success</span>',
                default => '<span class="badge bg-info">Info</span>'
            }
        );
    }

    /**
     * Accessor untuk label prioritas dengan huruf besar.
     *
     * @return Attribute
     */
    protected function priorityLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): string => ucfirst($this->priority)
        );
    }

    /**
     * Accessor untuk memformat waktu pembuatan yang human-friendly (misal: "2 hours ago").
     *
     * @return Attribute
     */
    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->created_at 
                ? $this->created_at->diffForHumans() 
                : '-'
        );
    }

    /**
     * Scope untuk menyaring notifikasi yang belum dibaca.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk menyaring notifikasi yang sudah dibaca.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope untuk mengurutkan notifikasi terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk memfilter notifikasi berdasarkan tingkat prioritas.
     *
     * @param Builder $query
     * @param string $priority
     * @return Builder
     */
    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', strtolower(trim($priority)));
    }
}
