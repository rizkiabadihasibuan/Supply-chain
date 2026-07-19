<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\Region;
use App\Repositories\Interfaces\RegionRepositoryInterface;
use App\Services\Contracts\RegionServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class RegionService implements RegionServiceInterface
{
    /**
     * @var RegionRepositoryInterface
     */
    protected RegionRepositoryInterface $regionRepo;

    /**
     * RegionService constructor.
     *
     * @param RegionRepositoryInterface $regionRepo
     */
    public function __construct(RegionRepositoryInterface $regionRepo)
    {
        $this->regionRepo = $regionRepo;
    }

    /**
     * @inheritDoc
     */
    public function getRegions(): Collection
    {
        return $this->regionRepo->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getRegionById(int $id): ?Region
    {
        return $this->regionRepo->findById($id);
    }
}
