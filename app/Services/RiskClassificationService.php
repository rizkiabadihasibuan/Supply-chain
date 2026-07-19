<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\RiskClassification;
use App\DTOs\RiskClassificationDTO;
use App\Mappers\RiskClassificationMapper;
use App\Repositories\Interfaces\RiskClassificationRepositoryInterface;
use App\Repositories\Interfaces\RiskCalculatorRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class RiskClassificationService
{
    protected RiskClassificationRepositoryInterface $classificationRepo;
    protected RiskCalculatorRepositoryInterface $calculatorRepo;
    protected RiskClassificationMapper $mapper;

    public function __construct(
        RiskClassificationRepositoryInterface $classificationRepo,
        RiskCalculatorRepositoryInterface $calculatorRepo,
        RiskClassificationMapper $mapper
    ) {
        $this->classificationRepo = $classificationRepo;
        $this->calculatorRepo = $calculatorRepo;
        $this->mapper = $mapper;
    }

    /**
     * Classify country risk score into categorical levels.
     *
     * @param Country $country
     * @param bool $forceRefresh
     * @return RiskClassificationDTO
     * @throws \InvalidArgumentException
     */
    public function classifyForCountry(Country $country, bool $forceRefresh = false): RiskClassificationDTO
    {
        $startTime = microtime(true);
        $cacheKey = "risk_classification_country_" . strtoupper($country->code);

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($country, $startTime) {
            // 1. Get latest score
            $score = $this->calculatorRepo->getLatestScore($country->id);

            if (!$score) {
                Log::warning("RiskClassification: Risk score not found for country {$country->code}. Initializing base score.");
                $score = new RiskScore([
                    'country_id' => $country->id,
                    'overall_score' => 0.00,
                    'risk_level' => 'Very Low',
                    'calculated_at' => now(),
                ]);
            }

            $overallScore = (float) $score->overall_score;

            // 2. Validation: out of bounds range check (0-100)
            if ($overallScore < 0.00 || $overallScore > 100.00) {
                throw new \InvalidArgumentException("Risk Score {$overallScore} for country {$country->code} is out of bounds (0 - 100).");
            }

            // 3. Get matching RiskClassification from DB
            $classification = $this->classificationRepo->getClassificationByScore($overallScore);

            if (!$classification) {
                Log::error("RiskClassification: Threshold configuration not found in DB for score {$overallScore}.");
                // Create temporary fallback model matching the config/risk.php thresholds
                $level = $this->determineLevelFromConfig($overallScore);
                $classification = new RiskClassification([
                    'name' => $level,
                    'color_code' => $this->getColorForLevel($level),
                ]);
            }

            // 4. Update the classification_id and level in the database score record
            if ($score->id && $classification->id) {
                $this->classificationRepo->updateScoreClassification(
                    $score->id,
                    $classification->id,
                    $classification->name
                );
            }

            // 5. Map to DTO
            $dto = $this->mapper->map(
                $country->name,
                $overallScore,
                $classification->name,
                $classification->color_code
            );

            $duration = (microtime(true) - $startTime) * 1000;
            Log::info("RiskClassification: Successfully classified {$country->code} as {$dto->riskLevel}", [
                'country' => $country->code,
                'risk_score' => $overallScore,
                'risk_level' => $dto->riskLevel,
                'priority' => $dto->priority,
                'duration_ms' => round($duration, 2),
            ]);

            return $dto;
        });
    }

    /**
     * Fallback level determination based on config thresholds.
     */
    private function determineLevelFromConfig(float $score): string
    {
        $veryLow = (float) Config::get('risk.thresholds.very_low', 20.0);
        $low = (float) Config::get('risk.thresholds.low', 40.0);
        $medium = (float) Config::get('risk.thresholds.medium', 60.0);
        $high = (float) Config::get('risk.thresholds.high', 80.0);

        return match (true) {
            $score <= $veryLow => 'Very Low',
            $score <= $low => 'Low',
            $score <= $medium => 'Medium',
            $score <= $high => 'High',
            default => 'Critical',
        };
    }

    /**
     * Fallback color codes mapping.
     */
    private function getColorForLevel(string $level): string
    {
        return match ($level) {
            'Very Low' => '#10B981',
            'Low' => '#3B82F6',
            'Medium' => '#F59E0B',
            'High' => '#EF4444',
            default => '#7F1D1D',
        };
    }
}
