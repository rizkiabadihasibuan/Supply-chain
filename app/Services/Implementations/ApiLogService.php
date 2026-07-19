<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\ApiLog;
use App\Repositories\Interfaces\ApiLogRepositoryInterface;
use App\Services\Contracts\ApiLogServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class ApiLogService implements ApiLogServiceInterface
{
    /**
     * @var ApiLogRepositoryInterface
     */
    protected ApiLogRepositoryInterface $apiLogRepo;

    /**
     * ApiLogService constructor.
     *
     * @param ApiLogRepositoryInterface $apiLogRepo
     */
    public function __construct(ApiLogRepositoryInterface $apiLogRepo)
    {
        $this->apiLogRepo = $apiLogRepo;
    }

    /**
     * @inheritDoc
     */
    public function logRequest(
        string $apiName,
        string $endpoint,
        string $method,
        ?int $statusCode,
        bool $isSuccess,
        ?string $errorMessage,
        int $durationMs
    ): ApiLog {
        return $this->apiLogRepo->log($apiName, $endpoint, $method, $statusCode, $isSuccess, $errorMessage, $durationMs);
    }

    /**
     * @inheritDoc
     */
    public function getRecentLogs(int $limit = 50): Collection
    {
        return $this->apiLogRepo->getLatestLogs($limit);
    }
}
