<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use App\Models\RiskSnapshot;
use App\Models\RiskScore;
use App\DTOs\RiskScoreDTO;
use App\Mappers\RiskScoreMapper;
use App\Repositories\Interfaces\RiskSnapshotRepositoryInterface;
use App\Repositories\Interfaces\RiskCalculatorRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class RiskCalculatorService
{
    protected RiskSnapshotRepositoryInterface $snapshotRepo;
    protected RiskCalculatorRepositoryInterface $calculatorRepo;
    protected RiskScoreMapper $mapper;

    public function __construct(
        RiskSnapshotRepositoryInterface $snapshotRepo,
        RiskCalculatorRepositoryInterface $calculatorRepo,
        RiskScoreMapper $mapper
    ) {
        $this->snapshotRepo = $snapshotRepo;
        $this->calculatorRepo = $calculatorRepo;
        $this->mapper = $mapper;
    }

    /**
     * Calculate risk score for a country based on its latest snapshot.
     *
     * @param Country $country
     * @param bool $forceRefresh
     * @return RiskScoreDTO
     */
    public function calculateForCountry(Country $country, bool $forceRefresh = false): RiskScoreDTO
    {
        $startTime = microtime(true);
        $cacheKey = "risk_calculation_country_" . strtoupper($country->code);

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($country, $startTime) {
            // 1. Get latest snapshot
            $snapshot = $this->snapshotRepo->getLatestForCountry($country->id);
            
            if (!$snapshot) {
                Log::warning("RiskCalculator: Snapshot not found for {$country->code}. Using default empty snapshot.");
                $snapshot = new RiskSnapshot([
                    'country_id' => $country->id,
                    'weather_data' => [],
                    'economic_data' => [],
                    'news_data' => [],
                    'port_data' => [],
                    'snapshot_time' => now(),
                ]);
            }

            // 2. Calculate Weather Score (0-100)
            $weatherScore = $this->calculateWeatherScore($snapshot->weather_data);

            // 3. Calculate Economic Score (0-100)
            $economicScore = $this->calculateEconomicScore($snapshot->economic_data);

            // 4. Calculate Political Score (0-100)
            $politicalScore = $this->calculatePoliticalScore($snapshot->news_data);

            // 5. Calculate Logistics Score (0-100)
            $logisticsScore = $this->calculateLogisticsScore($snapshot->port_data);

            // 6. Retrieve Bobot dari Config
            $weatherWeight = (float) Config::get('risk.weights.weather', 0.30);
            $economicWeight = (float) Config::get('risk.weights.economic', 0.20);
            $politicalWeight = (float) Config::get('risk.weights.political', 0.40);
            $logisticsWeight = (float) Config::get('risk.weights.logistics', 0.10);

            // 7. Weighted Formula
            $overallScore = ($weatherScore * $weatherWeight)
                + ($economicScore * $economicWeight)
                + ($politicalScore * $politicalWeight)
                + ($logisticsScore * $logisticsWeight);

            $overallScore = (float) max(0.00, min(100.00, $overallScore));

            // 8. Determine Risk Level
            $riskLevel = $this->determineRiskLevel($overallScore);

            // 9. Map to DTO
            $dto = $this->mapper->map(
                $country->id,
                $weatherScore,
                $economicScore,
                $politicalScore,
                $logisticsScore,
                $overallScore,
                $riskLevel
            );

            // 10. Persist to Database (risk_scores)
            $riskScore = $this->calculatorRepo->saveCalculatedScore($country->id, $dto);

            // 11. Record to History (for database history tracking)
            \App\Models\RiskHistory::create([
                'country_id' => $country->id,
                'risk_score_id' => $riskScore->id,
                'total_risk_score' => $overallScore,
                'overall_score' => $overallScore,
                'risk_level' => $riskLevel,
                'calculated_date' => now()->toDateString(),
                'recorded_at' => now(),
            ]);

            $duration = (microtime(true) - $startTime) * 1000;
            Log::info("RiskCalculator: Calculated overall score for {$country->code}: {$overallScore}", [
                'country' => $country->code,
                'calculation_time' => now()->toIso8601String(),
                'snapshot_id' => $snapshot->id,
                'overall_score' => $overallScore,
                'execution_time_ms' => round($duration, 2),
            ]);

            return $dto;
        });
    }

    /**
     * Normalize and calculate Weather Risk (0-100).
     */
    private function calculateWeatherScore(array $weatherData): float
    {
        $temp = (float) ($weatherData['temperature'] ?? 22.0);
        $wind = (float) ($weatherData['wind_speed'] ?? 0.0);
        $rain = (float) ($weatherData['rainfall'] ?? 0.0);

        // Normalize Temp: absolute difference from ideal 22C
        $tempDiff = abs($temp - 22.0);
        $normalizedTemp = min(100.0, ($tempDiff / 25.0) * 100.0);

        // Normalize Wind: calm (0) to storm (>= 100 km/h)
        $normalizedWind = min(100.0, ($wind / 80.0) * 100.0);

        // Normalize Rain: dry (0) to flood (>= 100 mm)
        $normalizedRain = min(100.0, ($rain / 70.0) * 100.0);

        return round(($normalizedTemp * 0.4) + ($normalizedWind * 0.3) + ($normalizedRain * 0.3), 2);
    }

    /**
     * Normalize and calculate Economic Risk (0-100).
     */
    private function calculateEconomicScore(array $economicData): float
    {
        $inflation = (float) ($economicData['inflation'] ?? 2.5);
        $gdpGrowth = (float) ($economicData['gdp_growth'] ?? 2.0);

        // Normalize Inflation: normal 2.5% is 0 risk, higher increases risk (capped at 20%)
        $inflationRisk = max(0.0, $inflation - 2.5);
        $normalizedInflation = min(100.0, ($inflationRisk / 15.0) * 100.0);

        // Normalize GDP: growth >= 5% is 0 risk, negative GDP growth increases risk (recession)
        $gdpRisk = max(0.0, 5.0 - $gdpGrowth);
        $normalizedGdp = min(100.0, ($gdpRisk / 10.0) * 100.0);

        return round(($normalizedInflation * 0.5) + ($normalizedGdp * 0.5), 2);
    }

    /**
     * Normalize and calculate Political Risk (0-100).
     */
    private function calculatePoliticalScore(array $newsData): float
    {
        if (empty($newsData)) {
            return 30.0; // Default baseline political risk
        }

        $negativeCount = 0;
        $totalCount = count($newsData);

        foreach ($newsData as $article) {
            $sentiment = strtolower($article['sentiment'] ?? '');
            if ($sentiment === 'negative' || str_contains(strtolower($article['title']), 'strike') || str_contains(strtolower($article['title']), 'disruption') || str_contains(strtolower($article['title']), 'war')) {
                $negativeCount++;
            }
        }

        return round(($negativeCount / $totalCount) * 100.0, 2);
    }

    /**
     * Normalize and calculate Logistics Risk (0-100).
     */
    private function calculateLogisticsScore(array $portData): float
    {
        if (empty($portData)) {
            return 25.0; // Default baseline logistics risk
        }

        $congestion = (float) ($portData['congestion_index'] ?? 2.5);
        return min(100.0, round($congestion * 10.0, 2));
    }

    /**
     * Map overall score to categorical level based on config thresholds.
     */
    private function determineRiskLevel(float $score): string
    {
        $low = (float) Config::get('risk.thresholds.low', 30.0);
        $medium = (float) Config::get('risk.thresholds.medium', 60.0);
        $high = (float) Config::get('risk.thresholds.high', 80.0);

        return match (true) {
            $score <= $low => 'Low',
            $score <= $medium => 'Medium',
            $score <= $high => 'High',
            default => 'Critical',
        };
    }
}
