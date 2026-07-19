<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface RiskSnapshotRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get latest snapshot for a country.
     *
     * @param int $countryId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getLatestForCountry(int $countryId);
}
