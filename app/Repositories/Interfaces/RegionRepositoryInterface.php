<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Region;
use Illuminate\Database\Eloquent\Collection;

interface RegionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search regions by name.
     *
     * @param string $term
     * @return Collection<int, Region>
     */
    public function search(string $term): Collection;

    /**
     * Filter regions.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Region>
     */
    public function filter(array $filters): Collection;
}
