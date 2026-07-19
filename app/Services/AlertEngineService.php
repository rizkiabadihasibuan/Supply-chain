<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\RiskSnapshot;
use App\Models\RiskTrend;
use App\Models\RiskAlert;
use App\DTOs\AlertDTO;
use App\Mappers\AlertMapper;
use App\Repositories\Interfaces\RiskAlertRepositoryInterface;
use App\Exceptions\AlertRuleConfigurationException;
use App\Exceptions\MissingRiskDataException;
use App\Exceptions\UnexpectedRiskException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AlertEngineService
{
    protected RiskAlertRepositoryInterface $alertRepo;
    protected AlertRuleService $ruleService;
    protected AlertMapper $mapper;
    protected RiskTrendService $trendService;

    public function __construct(
        RiskAlertRepositoryInterface $alertRepo,
        AlertRuleService $ruleService,
        AlertMapper $mapper,
        RiskTrendService $trendService
    ) {
        $this->alertRepo = $alertRepo;
        $this->ruleService = $ruleService;
        $this->mapper = $mapper;
        $this->trendService = $trendService;
    }

    /**
     * Get active alerts for a country from cache or calculate them.
     */
    public function remember(Country $country): array
    {
        $cacheKey = "country_alerts_" . strtoupper($country->code);
        $ttl = (int) Config::get('risk.cache_ttl', 3600);

        return Cache::remember($cacheKey, now()->addSeconds($ttl), function () use ($country) {
            $models = $this->alertRepo->getActiveForCountry($country->id);
            $trend = RiskTrend::where('country_id', $country->id)->orderBy('analyzed_at', 'desc')->first();
            
            $dtos = [];
            foreach ($models as $model) {
                $dtos[] = $this->mapper->mapModelToDTO($model, $trend);
            }
            return $dtos;
        });
    }

    /**
     * Clear cached alerts for a country.
     */
    public function forget(Country $country): bool
    {
        $cacheKey = "country_alerts_" . strtoupper($country->code);
        return Cache::forget($cacheKey);
    }

    /**
     * Refresh alert generation and cache.
     */
    public function refresh(Country $country): array
    {
        $this->forget($country);
        return $this->generateAlertsForCountry($country);
    }

    /**
     * Generate alerts based on latest score, trend and snapshot.
     *
     * @throws MissingRiskDataException
     * @throws AlertRuleConfigurationException
     * @throws UnexpectedRiskException
     */
    public function generateAlertsForCountry(Country $country): array
    {
        $startTime = microtime(true);

        try {
            // 1. Fetch latest RiskScore
            $score = RiskScore::where('country_id', $country->id)
                ->orderBy('calculated_at', 'desc')
                ->first();

            if (!$score) {
                throw new MissingRiskDataException("No RiskScore data found for country: " . $country->name);
            }

            // 2. Fetch latest RiskTrend or evaluate it dynamically
            $trendModel = RiskTrend::where('country_id', $country->id)
                ->orderBy('analyzed_at', 'desc')
                ->first();

            if ($trendModel) {
                // Map model fields to match RiskTrendDTO constructor
                $trendDTO = new \App\DTOs\RiskTrendDTO([
                    'countryName' => $country->name,
                    'currentScore' => $trendModel->current_score,
                    'previousScore' => $trendModel->previous_score,
                    'scoreDifference' => $trendModel->current_score - $trendModel->previous_score,
                    'percentageChange' => $trendModel->change_percentage,
                    'trendDirection' => $trendModel->trend_direction,
                    'analysisTime' => $trendModel->analyzed_at->toIso8601String(),
                ]);
            } else {
                // Try to calculate trend
                try {
                    $trendDTO = $this->trendService->analyzeForCountry($country);
                } catch (\Throwable $e) {
                    throw new MissingRiskDataException("No RiskTrend data available and calculation failed for country: " . $country->name, 0, $e);
                }
            }

            // 3. Fetch latest RiskSnapshot
            $snapshot = RiskSnapshot::where('country_id', $country->id)
                ->orderBy('snapshot_time', 'desc')
                ->first();

            // 4. Validate Rule Configuration exists
            $rules = Config::get('risk.alert_rules');
            if (is_null($rules)) {
                throw new AlertRuleConfigurationException("Alert rules are not defined in config/risk.php.");
            }

            // 5. Evaluate rules
            $triggeredAlerts = $this->ruleService->evaluateRules($country, $score, $trendDTO, $snapshot);

            $savedAlerts = [];

            foreach ($triggeredAlerts as $alertSpec) {
                // De-duplication check: If an active alert of the same type and score already exists, skip
                $existing = RiskAlert::where('country_id', $country->id)
                    ->where('risk_score_id', $score->id)
                    ->where('alert_type', $alertSpec['alert_type'])
                    ->where('status', 'New')
                    ->first();

                if ($existing) {
                    $savedAlerts[] = $existing;
                    continue;
                }

                // Persist new alert
                $alertModel = $this->alertRepo->create([
                    'country_id' => $country->id,
                    'risk_score_id' => $score->id,
                    'alert_type' => $alertSpec['alert_type'],
                    'severity' => $alertSpec['severity'],
                    'title' => $alertSpec['title'],
                    'description' => $alertSpec['description'],
                    'status' => 'New',
                ]);

                $savedAlerts[] = $alertModel;

                // Logging details
                $duration = (microtime(true) - $startTime) * 1000;
                Log::info("AlertEngineService: Alert generated", [
                    'country' => $country->code,
                    'alert_type' => $alertSpec['alert_type'],
                    'severity' => $alertSpec['severity'],
                    'rule_trigger' => "threshold exceeded",
                    'execution_time_ms' => round($duration, 2),
                ]);
            }

            // Invalidate/refresh cache
            $this->forget($country);
            return $this->remember($country);

        } catch (MissingRiskDataException | AlertRuleConfigurationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error("AlertEngineService: Unexpected exception: " . $e->getMessage(), ['exception' => $e]);
            throw new UnexpectedRiskException("Unexpected error during alert evaluation: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Update the status of an alert with validation rules.
     *
     * @throws \App\Exceptions\InvalidAlertStatusTransitionException
     */
    public function updateStatus(RiskAlert $alert, string $newStatus): RiskAlert
    {
        $currentStatus = $alert->status;

        // Define valid transitions
        $validTransitions = [
            'Active' => ['New', 'Acknowledged', 'Resolved', 'Archived'],
            'New' => ['Acknowledged', 'Resolved', 'Archived'],
            'Acknowledged' => ['Resolved', 'Archived'],
            'Resolved' => ['Archived'],
            'Archived' => [],
        ];

        $newStatus = trim($newStatus);

        if (!array_key_exists($currentStatus, $validTransitions)) {
            $validTransitions[$currentStatus] = ['Acknowledged', 'Resolved', 'Archived'];
        }

        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
            throw new \App\Exceptions\InvalidAlertStatusTransitionException(
                "Invalid status transition from '{$currentStatus}' to '{$newStatus}' for Alert ID: {$alert->id}"
            );
        }

        $alert->status = $newStatus;
        if ($newStatus === 'Resolved') {
            $alert->resolved_at = now();
        }
        $alert->save();

        if ($alert->country) {
            $this->forget($alert->country);
        }

        return $alert;
    }
}
