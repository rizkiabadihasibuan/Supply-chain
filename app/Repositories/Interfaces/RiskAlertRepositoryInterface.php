<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface RiskAlertRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get active alerts for a country.
     *
     * @param int $countryId
     * @return Collection
     */
    public function getActiveForCountry(int $countryId): Collection;
}
