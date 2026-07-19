<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\DTOs\RiskAggregationDTO;
use App\Models\RiskSnapshot;
use App\Repositories\Interfaces\RiskAggregationRepositoryInterface;

class RiskAggregationRepository extends BaseRepository implements RiskAggregationRepositoryInterface
{
    public function __construct(RiskSnapshot $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function saveSnapshot(int $countryId, RiskAggregationDTO $dto): RiskSnapshot
    {
        return $this->create([
            'country_id' => $countryId,
            'weather_data' => $dto->weather,
            'economic_data' => $dto->economic,
            'news_data' => $dto->news,
            'port_data' => $dto->ports,
            'overall_status' => $dto->weather['condition'] === 'Stormy' ? 'Warning' : 'Normal',
            'snapshot_time' => $dto->timestamp,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getLatestSnapshot(int $countryId): ?RiskSnapshot
    {
        return $this->model->newQuery()
            ->where('country_id', $countryId)
            ->orderBy('snapshot_time', 'desc')
            ->first();
    }
}
