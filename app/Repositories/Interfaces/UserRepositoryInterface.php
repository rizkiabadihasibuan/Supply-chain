<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find a user by email address.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Search users by name or email.
     *
     * @param string $term
     * @return Collection<int, User>
     */
    public function search(string $term): Collection;

    /**
     * Filter users by role.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, User>
     */
    public function filter(array $filters): Collection;
}
