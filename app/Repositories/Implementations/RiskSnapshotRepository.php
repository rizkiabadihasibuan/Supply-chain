<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\RiskSnapshot;
use App\Repositories\Interfaces\RiskSnapshotRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class RiskSnapshotRepository extends BaseRepository implements RiskSnapshotRepositoryInterface
{
    public function __construct(RiskSnapshot $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getLatestForCountry(int $countryId): ?Model
    {
        return $this->model->newQuery()
            ->where('country_id', $countryId)
            ->orderBy('snapshot_time', 'desc')
            ->first();
    }
}
