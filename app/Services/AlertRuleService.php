<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\RiskSnapshot;
use App\DTOs\RiskTrendDTO;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AlertRuleService
{
    /**
     * Evaluate risk parameters against alert rules.
     *
     * @return array
     */
    public function evaluateRules(
        Country $country,
        RiskScore $score,
        RiskTrendDTO $trend,
        ?RiskSnapshot $snapshot = null
    ): array {
        $triggered = [];
        $rules = Config::get('risk.alert_rules', []);

        if (empty($rules)) {
            Log::warning("AlertRuleService: No alert rules configured in config/risk.php.");
            return [];
        }

        // Validate all configurations
        foreach (['overall_risk', 'weather', 'economic', 'political', 'logistics', 'trend', 'ranking', 'classification_change', 'data_quality'] as $key) {
            $this->validateRuleConfig($key, $rules[$key] ?? null);
        }

        // 1. Overall Risk Alert
        if ($rules['overall_risk']['enabled'] ?? false) {
            $threshold = (float) ($rules['overall_risk']['threshold'] ?? 75.0);
            if ($score->final_risk_score >= $threshold) {
                $triggered[] = [
                    'alert_type' => 'Overall Risk Alert',
                    'severity' => $rules['overall_risk']['severity'] ?? 'High',
                    'title' => 'Overall Risk Alert: ' . $country->name,
                    'description' => "Overall risk score of {$score->final_risk_score} has reached or exceeded the critical threshold of {$threshold}.",
                ];
            }
        }

        // 2. Weather Alert
        if ($rules['weather']['enabled'] ?? false) {
            $threshold = (float) ($rules['weather']['threshold'] ?? 70.0);
            if ($score->weather_score >= $threshold) {
                $triggered[] = [
                    'alert_type' => 'Weather Alert',
                    'severity' => $rules['weather']['severity'] ?? 'Medium',
                    'title' => 'Weather Disruption Alert: ' . $country->name,
                    'description' => "Weather risk score of {$score->weather_score} has reached or exceeded the threshold of {$threshold}.",
                ];
            }
        }

        // 3. Economic Alert
        if ($rules['economic']['enabled'] ?? false) {
            $threshold = (float) ($rules['economic']['threshold'] ?? 60.0);
            if ($score->economic_score >= $threshold) {
                $triggered[] = [
                    'alert_type' => 'Economic Alert',
                    'severity' => $rules['economic']['severity'] ?? 'Medium',
                    'title' => 'Economic Risk Alert: ' . $country->name,
                    'description' => "Economic risk score of {$score->economic_score} has reached or exceeded the threshold of {$threshold}.",
                ];
            }
        }

        // 4. Political Alert
        if ($rules['political']['enabled'] ?? false) {
            $threshold = (float) ($rules['political']['threshold'] ?? 65.0);
            if ($score->political_score >= $threshold) {
                $triggered[] = [
                    'alert_type' => 'Political Alert',
                    'severity' => $rules['political']['severity'] ?? 'High',
                    'title' => 'Political Instability Alert: ' . $country->name,
                    'description' => "Political/News risk score of {$score->political_score} has reached or exceeded the threshold of {$threshold}.",
                ];
            }
        }

        // 5. Logistics Alert
        if ($rules['logistics']['enabled'] ?? false) {
            $threshold = (float) ($rules['logistics']['threshold'] ?? 70.0);
            if ($score->logistics_score >= $threshold) {
                $triggered[] = [
                    'alert_type' => 'Logistics Alert',
                    'severity' => $rules['logistics']['severity'] ?? 'Medium',
                    'title' => 'Logistics Congestion Alert: ' . $country->name,
                    'description' => "Logistics/Port congestion risk score of {$score->logistics_score} has reached or exceeded the threshold of {$threshold}.",
                ];
            }
        }

        // 6. Trend Alert
        if ($rules['trend']['enabled'] ?? false) {
            $threshold = (float) ($rules['trend']['threshold'] ?? 15.0);
            if (abs($trend->percentageChange) >= $threshold) {
                $triggered[] = [
                    'alert_type' => 'Trend Alert',
                    'severity' => $rules['trend']['severity'] ?? 'High',
                    'title' => 'Risk Trend Alert: ' . $country->name,
                    'description' => "Absolute risk score change of {$trend->percentageChange}% exceeded the volatility threshold of {$threshold}%.",
                ];
            }
        }

        // 7. Ranking Alert
        if ($rules['ranking']['enabled'] ?? false) {
            $threshold = (int) ($rules['ranking']['threshold'] ?? 3);
            $rankDrop = $trend->currentRank - $trend->previousRank;
            if ($rankDrop >= $threshold) {
                $triggered[] = [
                    'alert_type' => 'Ranking Alert',
                    'severity' => $rules['ranking']['severity'] ?? 'Medium',
                    'title' => 'Risk Rank Drop Alert: ' . $country->name,
                    'description' => "Supply chain risk ranking dropped by {$rankDrop} positions (from rank {$trend->previousRank} to {$trend->currentRank}).",
                ];
            }
        }

        // 8. Classification Alert
        if ($rules['classification_change']['enabled'] ?? false) {
            if ($trend->classificationDifference !== 0) {
                $direction = $trend->classificationDifference > 0 ? 'increased' : 'decreased';
                $triggered[] = [
                    'alert_type' => 'Classification Alert',
                    'severity' => $rules['classification_change']['severity'] ?? 'Info',
                    'title' => 'Risk Level Classification Change: ' . $country->name,
                    'description' => "Risk level classification has {$direction} from previous tier.",
                ];
            }
        }

        // 9. Data Quality Alert
        if ($rules['data_quality']['enabled'] ?? false) {
            if ($snapshot) {
                $missing = [];
                if (empty($snapshot->weather_data)) { $missing[] = 'weather'; }
                if (empty($snapshot->economic_data)) { $missing[] = 'economic'; }
                if (empty($snapshot->news_data)) { $missing[] = 'news'; }
                if (empty($snapshot->port_data)) { $missing[] = 'port'; }

                if (!empty($missing)) {
                    $missingList = implode(', ', $missing);
                    $triggered[] = [
                        'alert_type' => 'Data Quality Alert',
                        'severity' => $rules['data_quality']['severity'] ?? 'Low',
                        'title' => 'Data Quality Warning: ' . $country->name,
                        'description' => "Risk snapshot contains empty or missing data fields for: {$missingList}.",
                    ];
                }
            } else {
                $triggered[] = [
                    'alert_type' => 'Data Quality Alert',
                    'severity' => $rules['data_quality']['severity'] ?? 'Low',
                    'title' => 'Missing Data Snapshot: ' . $country->name,
                    'description' => "No active risk snapshot found to calculate data points.",
                ];
            }
        }

        return $triggered;
    }

    /**
     * Validate individual rule configuration format.
     *
     * @throws \App\Exceptions\AlertRuleConfigurationException
     */
    private function validateRuleConfig(string $key, ?array $config): void
    {
        if (is_null($config)) {
            throw new \App\Exceptions\AlertRuleConfigurationException("Configuration for alert rule '{$key}' is missing.");
        }

        if ($config['enabled'] ?? false) {
            // Check threshold for rules that need it
            if (!in_array($key, ['classification_change', 'data_quality'])) {
                if (!isset($config['threshold'])) {
                    throw new \App\Exceptions\AlertRuleConfigurationException("Missing required 'threshold' property for enabled alert rule '{$key}'.");
                }
            }
            // Check severity
            if (!isset($config['severity'])) {
                throw new \App\Exceptions\AlertRuleConfigurationException("Missing required 'severity' property for enabled alert rule '{$key}'.");
            }
        }
    }
}
