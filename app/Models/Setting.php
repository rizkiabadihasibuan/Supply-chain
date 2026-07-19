<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 *
 * @package App\Models
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string $group
 * @property string|null $description
 * @property bool $autoload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Setting extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'description',
        'autoload',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'autoload' => 'boolean',
    ];

    /**
     * Mutator & Accessor untuk Key setting (selalu lowercase, trim, ganti spasi dengan underscore).
     *
     * @return Attribute
     */
    protected function key(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => strtolower($value),
            set: fn (string $value): string => strtolower(str_replace(' ', '_', trim($value)))
        );
    }

    /**
     * Scope untuk menyaring setelan yang otomatis dimuat (autoload = true).
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAutoload(Builder $query): Builder
    {
        return $query->where('autoload', true);
    }

    /**
     * Scope untuk menyaring setelan berdasarkan grup setelan.
     *
     * @param Builder $query
     * @param string $group
     * @return Builder
     */
    public function scopeByGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', strtolower(trim($group)));
    }
}
