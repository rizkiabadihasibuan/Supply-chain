<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search categories by name.
     *
     * @param string $term
     * @return Collection
     */
    public function search(string $term): Collection;

    /**
     * Filter categories.
     *
     * @param array<string, mixed> $filters
     * @return Collection
     */
    public function filter(array $filters): Collection;

    /**
     * Get categories by domain type.
     *
     * @param string $type
     * @return Collection
     */
    public function getByType(string $type): Collection;
}
