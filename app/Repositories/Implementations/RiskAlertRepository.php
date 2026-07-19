<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\RiskAlert;
use App\Repositories\Interfaces\RiskAlertRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RiskAlertRepository extends BaseRepository implements RiskAlertRepositoryInterface
{
    public function __construct(RiskAlert $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getActiveForCountry(int $countryId): Collection
    {
        return $this->model->newQuery()
            ->where('country_id', $countryId)
            ->whereIn('status', ['Active', 'New', 'Acknowledged'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
