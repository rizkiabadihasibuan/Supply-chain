<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiLog
 *
 * @package App\Models
 * @property int $id
 * @property string $api_name
 * @property string $endpoint
 * @property string $method
 * @property int|null $status_code
 * @property bool $is_success
 * @property string|null $error_message
 * @property int $duration_ms
 * @property Carbon|null $created_at
 * @property-read string $formatted_response_time
 */
class ApiLog extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'api_logs';

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
        'api_name',
        'endpoint',
        'method',
        'status_code',
        'is_success',
        'error_message',
        'duration_ms',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status_code' => 'integer',
        'is_success' => 'boolean',
        'duration_ms' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Scope untuk menyaring log kegagalan API.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeErrors(Builder $query): Builder
    {
        return $query->where('is_success', false);
    }

    /**
     * Scope untuk menyaring log kesuksesan API.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeSuccess(Builder $query): Builder
    {
        return $query->where('is_success', true);
    }

    /**
     * Scope untuk memfilter berdasarkan nama API penyedia.
     *
     * @param Builder $query
     * @param string $apiName
     * @return Builder
     */
    public function scopeByApi(Builder $query, string $apiName): Builder
    {
        return $query->where('api_name', $apiName);
    }

    /**
     * Scope untuk mengurutkan data terbaru.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessor untuk memformat durasi latensi response (misal: "350ms").
     *
     * @return Attribute
     */
    protected function formattedResponseTime(): Attribute
    {
        return Attribute::make(
            get: fn (): string => sprintf('%sms', $this->duration_ms)
        );
    }

    /**
     * Mutator untuk memotong query string yang tidak perlu dari path endpoint.
     *
     * @return Attribute
     */
    protected function endpoint(): Attribute
    {
        return Attribute::make(
            set: fn (string $value): string => trim($value)
        );
    }
}
