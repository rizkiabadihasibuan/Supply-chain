<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Region;
use Illuminate\Database\Eloquent\Collection;

interface RegionServiceInterface
{
    /**
     * Get all regions.
     *
     * @return Collection<int, Region>
     */
    public function getRegions(): Collection;

    /**
     * Find region by ID.
     *
     * @param int $id
     * @return Region|null
     */
    public function getRegionById(int $id): ?Region;
}
