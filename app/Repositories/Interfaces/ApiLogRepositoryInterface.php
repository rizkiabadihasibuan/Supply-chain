<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\ApiLog;
use Illuminate\Database\Eloquent\Collection;

interface ApiLogRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Create an API log entry.
     *
     * @param string $apiName
     * @param string $endpoint
     * @param string $method
     * @param int|null $statusCode
     * @param bool $isSuccess
     * @param string|null $errorMessage
     * @param int $durationMs
     * @return ApiLog
     */
    public function log(
        string $apiName,
        string $endpoint,
        string $method,
        ?int $statusCode,
        bool $isSuccess,
        ?string $errorMessage,
        int $durationMs
    ): ApiLog;

    /**
     * Get recent API log entries.
     *
     * @param int $limit
     * @return Collection<int, ApiLog>
     */
    public function getLatestLogs(int $limit = 50): Collection;

    /**
     * Get error logs.
     *
     * @return Collection<int, ApiLog>
     */
    public function getErrors(): Collection;
}
