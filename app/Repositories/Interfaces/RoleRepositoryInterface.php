<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find a role by its name.
     *
     * @param string $name
     * @return Role|null
     */
    public function findByName(string $name): ?Role;

    /**
     * Search roles by name.
     *
     * @param string $term
     * @return Collection<int, Role>
     */
    public function search(string $term): Collection;

    /**
     * Filter roles.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Role>
     */
    public function filter(array $filters): Collection;
}
