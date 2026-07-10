<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
        'permissions',
    ];

    /**
     * Cast permissions JSON column to array.
     */
    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Get the users associated with this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
