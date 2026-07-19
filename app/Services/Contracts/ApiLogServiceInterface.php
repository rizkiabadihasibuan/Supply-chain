<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\ApiLog;
use Illuminate\Database\Eloquent\Collection;

interface ApiLogServiceInterface
{
    /**
     * Log external API request details.
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
    public function logRequest(
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
    public function getRecentLogs(int $limit = 50): Collection;
}
