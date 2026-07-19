<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\RiskRankingDTO;
use App\Mappers\RiskRankingMapper;
use App\Repositories\Interfaces\RiskRankingRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class RiskRankingService
{
    protected RiskRankingRepositoryInterface $repository;
    protected RiskRankingMapper $mapper;

    public function __construct(
        RiskRankingRepositoryInterface $repository,
        RiskRankingMapper $mapper
    ) {
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    /**
     * Get ranked country risk list based on filters and sorting criteria.
     *
     * @param array $filters
     * @param string $sortBy
     * @param string $direction
     * @param int|null $limit
     * @param bool $forceRefresh
     * @return array<RiskRankingDTO>
     */
    public function getRanking(
        array $filters = [],
        string $sortBy = 'overall_score',
        string $direction = 'desc',
        ?int $limit = null,
        bool $forceRefresh = false
    ): array {
        $startTime = microtime(true);
        
        $cacheSuffix = md5(serialize($filters) . $sortBy . $direction . (string) $limit);
        $cacheKey = "risk_ranking_{$cacheSuffix}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        $cacheStatus = 'HIT';
        $rankingData = Cache::remember($cacheKey, now()->addHours(6), function () use ($filters, $sortBy, $direction, $limit, &$cacheStatus) {
            $cacheStatus = 'MISS';
            
            try {
                $scores = $this->repository->getLatestScoresForRanking($filters, $sortBy, $direction, $limit);
                return $this->mapper->mapMany($scores);
            } catch (\Throwable $e) {
                Log::error("RiskRankingService: Database ranking query failed: " . $e->getMessage(), [
                    'exception' => $e
                ]);
                throw $e;
            }
        });

        $duration = (microtime(true) - $startTime) * 1000;
        Log::info("RiskRankingService: Ranked countries list calculated.", [
            'ranking_type' => $sortBy,
            'countries_count' => count($rankingData),
            'execution_time_ms' => round($duration, 2),
            'cache_status' => $cacheStatus,
        ]);

        return $rankingData;
    }

    /**
     * Get top N countries with highest risk score.
     */
    public function getTopHighest(int $limit = 10, bool $forceRefresh = false): array
    {
        return $this->getRanking([], 'overall_score', 'desc', $limit, $forceRefresh);
    }

    /**
     * Get top N countries with lowest risk score.
     */
    public function getTopLowest(int $limit = 10, bool $forceRefresh = false): array
    {
        return $this->getRanking([], 'overall_score', 'asc', $limit, $forceRefresh);
    }
}
