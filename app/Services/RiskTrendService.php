<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use App\Models\RiskHistory;
use App\Models\RiskTrend;
use App\DTOs\RiskTrendDTO;
use App\Mappers\RiskTrendMapper;
use App\Repositories\Interfaces\RiskTrendRepositoryInterface;
use App\Repositories\Interfaces\RiskRankingRepositoryInterface;
use App\Exceptions\RiskHistoryNotFoundException;
use App\Exceptions\IncompleteRiskDataException;
use App\Exceptions\UnexpectedRiskException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class RiskTrendService
{
    protected RiskTrendRepositoryInterface $trendRepo;
    protected RiskRankingRepositoryInterface $rankingRepo;
    protected CountryService $countryService;
    protected RiskTrendMapper $mapper;

    public function __construct(
        RiskTrendRepositoryInterface $trendRepo,
        RiskRankingRepositoryInterface $rankingRepo,
        CountryService $countryService,
        RiskTrendMapper $mapper
    ) {
        $this->trendRepo = $trendRepo;
        $this->rankingRepo = $rankingRepo;
        $this->countryService = $countryService;
        $this->mapper = $mapper;
    }

    /**
     * Get trend from cache or calculate it.
     */
    public function remember(Country $country): RiskTrendDTO
    {
        return $this->analyzeForCountry($country, false);
    }

    /**
     * Clear cached trend for a country.
     */
    public function forget(Country $country): bool
    {
        $cacheKey = "risk_trend_country_" . strtoupper($country->code);
        return Cache::forget($cacheKey);
    }

    /**
     * Calculate and cache trend for a country, overriding existing cache.
     */
    public function refresh(Country $country): RiskTrendDTO
    {
        return $this->analyzeForCountry($country, true);
    }

    /**
     * Analyze and calculate risk trend for a country.
     *
     * @param Country $country
     * @param bool $forceRefresh
     * @return RiskTrendDTO
     * @throws RiskHistoryNotFoundException
     * @throws IncompleteRiskDataException
     * @throws UnexpectedRiskException
     */
    public function analyzeForCountry(Country $country, bool $forceRefresh = false): RiskTrendDTO
    {
        try {
            $startTime = microtime(true);
            $cacheKey = "risk_trend_country_" . strtoupper($country->code);

            if ($forceRefresh) {
                Cache::forget($cacheKey);
            }

            $ttl = (int) Config::get('risk.cache_ttl', 3600);

            return Cache::remember($cacheKey, now()->addSeconds($ttl), function () use ($country, $startTime) {
                // 1. Fetch risk histories for the country
                $history = RiskHistory::query()
                    ->where('country_id', $country->id)
                    ->orderBy('recorded_at', 'desc')
                    ->limit(2)
                    ->get();

                if ($history->isEmpty()) {
                    Log::warning("RiskTrendService: No history found for {$country->code}.");
                    throw new RiskHistoryNotFoundException("Risk history not found for country: " . $country->name);
                }

                if ($history->count() < 2) {
                    Log::warning("RiskTrendService: Incomplete risk history for {$country->code}. Need at least 2 records.");
                    throw new IncompleteRiskDataException("Incomplete risk history data for country: " . $country->name);
                }

                $currentHistory = $history->first();
                $previousHistory = $history->get(1);

                $currentScore = (float) $currentHistory->overall_score;
                $previousScore = (float) $previousHistory->overall_score;

                // 2. Score Difference and Percentage Change
                $scoreDifference = round($currentScore - $previousScore, 2);
                
                // Safe Division check to prevent DivisionByZero
                $percentageChange = 0.0;
                if ($previousScore != 0.0) {
                    $percentageChange = round(($scoreDifference / $previousScore) * 100, 2);
                }

                // 3. Compute dynamic ranks
                $countries = $this->countryService->getAllCountries();
                $currentScores = [];
                $previousScores = [];

                foreach ($countries as $c) {
                    $cHistory = RiskHistory::query()
                        ->where('country_id', $c->id)
                        ->orderBy('recorded_at', 'desc')
                        ->limit(2)
                        ->get();

                    $cCurrent = $cHistory->first() ? (float) $cHistory->first()->overall_score : 0.0;
                    $currentScores[$c->id] = $cCurrent;
                    
                    $cPrevious = $cHistory->count() > 1
                        ? (float) $cHistory->get(1)->overall_score
                        : $cCurrent;
                    $previousScores[$c->id] = $cPrevious;
                }

                // Rank current
                arsort($currentScores);
                $currentRanks = [];
                $rank = 1;
                foreach ($currentScores as $cid => $val) {
                    $currentRanks[$cid] = $rank++;
                }

                // Rank previous
                arsort($previousScores);
                $previousRanks = [];
                $rank = 1;
                foreach ($previousScores as $cid => $val) {
                    $previousRanks[$cid] = $rank++;
                }

                $currentRank = $currentRanks[$country->id] ?? 1;
                $previousRank = $previousRanks[$country->id] ?? 1;
                $rankDifference = $previousRank - $currentRank;

                // 4. Trend Direction and Strength
                $trendDirection = $this->determineTrendDirection($scoreDifference);
                $trendStrength = $this->determineTrendStrength($scoreDifference);

                // 5. Classification Difference
                $currentLevel = $currentHistory->risk_level ?? 'Low';
                $previousLevel = $previousHistory->risk_level ?? 'Low';
                $classificationDifference = $this->getClassificationTier($currentLevel) - $this->getClassificationTier($previousLevel);

                // 6. Map to DTO
                $dto = $this->mapper->map(
                    $country->name,
                    $currentScore,
                    $previousScore,
                    $scoreDifference,
                    $percentageChange,
                    $previousRank,
                    $currentRank,
                    $rankDifference,
                    $classificationDifference,
                    $trendDirection,
                    $trendStrength
                );

                // 7. Save snapshot of the trend to Database (risk_trends) - prevent duplicates
                $latestTrend = $this->trendRepo->getForCountry($country->id, 1)->first();
                $alreadySaved = $latestTrend && $latestTrend->analyzed_at->gte($currentHistory->recorded_at);

                if (!$alreadySaved) {
                    $this->trendRepo->saveTrend($country->id, $dto);
                } else {
                    Log::info("RiskTrendService: Trend already analyzed and saved for {$country->code} at {$currentHistory->recorded_at->toIso8601String()}. Skipping database insert.");
                }

                $duration = (microtime(true) - $startTime) * 1000;
                Log::info("RiskTrendService: Trend analyzed for {$country->code}", [
                    'country' => $country->code,
                    'current_score' => $currentScore,
                    'previous_score' => $previousScore,
                    'difference' => $scoreDifference,
                    'trend_direction' => $trendDirection,
                    'execution_time_ms' => round($duration, 2),
                ]);

                return $dto;
            });
        } catch (RiskHistoryNotFoundException | IncompleteRiskDataException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error("RiskTrendService: Unexpected error during trend analysis: " . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw new UnexpectedRiskException("Unexpected error during risk trend analysis: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Determine direction based on score difference.
     */
    private function determineTrendDirection(float $diff): string
    {
        if ($diff > 0.0) {
            return 'Up';
        }
        if ($diff < 0.0) {
            return 'Down';
        }
        return 'Stable';
    }

    /**
     * Determine strength based on absolute difference matching config thresholds.
     */
    private function determineTrendStrength(float $diff): string
    {
        $absDiff = abs($diff);
        $weak = (float) Config::get('risk.trend_thresholds.weak', 2.0);
        $moderate = (float) Config::get('risk.trend_thresholds.moderate', 5.0);
        $strong = (float) Config::get('risk.trend_thresholds.strong', 10.0);
        $critical = (float) Config::get('risk.trend_thresholds.critical', 15.0);

        return match (true) {
            $absDiff <= $weak => 'Weak',
            $absDiff <= $moderate => 'Moderate',
            $absDiff <= $strong => 'Strong',
            $absDiff < $critical => 'Strong',
            default => 'Critical',
        };
    }

    /**
     * Get numeric tier index for classification level.
     */
    private function getClassificationTier(string $level): int
    {
        return match (strtolower(str_replace('_', ' ', $level))) {
            'very low' => 1,
            'low'      => 2,
            'medium'   => 3,
            'high'     => 4,
            'critical' => 5,
            default    => 0,
        };
    }
}
