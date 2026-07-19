<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Language
 *
 * @package App\Models
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Relasi Many-to-Many ke Countries.
     *
     * @return BelongsToMany
     */
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_languages');
    }

    /**
     * Mutator & Accessor untuk kode bahasa (selalu lowercase).
     *
     * @return Attribute
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => strtolower($value),
            set: fn (string $value): string => strtolower(trim($value))
        );
    }
}
