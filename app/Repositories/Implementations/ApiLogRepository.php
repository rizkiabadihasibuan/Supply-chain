<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\ApiLog;
use App\Repositories\Interfaces\ApiLogRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApiLogRepository extends BaseRepository implements ApiLogRepositoryInterface
{
    /**
     * ApiLogRepository constructor.
     *
     * @param ApiLog $model
     */
    public function __construct(ApiLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function log(
        string $apiName,
        string $endpoint,
        string $method,
        ?int $statusCode,
        bool $isSuccess,
        ?string $errorMessage,
        int $durationMs
    ): ApiLog {
        return $this->create([
            'api_name' => $apiName,
            'endpoint' => $endpoint,
            'method' => $method,
            'status_code' => $statusCode,
            'is_success' => $isSuccess,
            'error_message' => $errorMessage,
            'duration_ms' => $durationMs,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getLatestLogs(int $limit = 50): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): Collection
    {
        return $this->model->errors()->latest()->get();
    }
}
