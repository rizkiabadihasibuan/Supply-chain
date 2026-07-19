<?php

declare(strict_types=1);

namespace App\Mappers;

use App\DTOs\AlertDTO;
use App\Models\RiskAlert;
use App\Models\RiskTrend;

class AlertMapper
{
    public function mapModelToDTO(RiskAlert $model, ?RiskTrend $trend = null): AlertDTO
    {
        $currentScore = $model->riskScore ? (float) $model->riskScore->final_risk_score : 0.0;
        $previousScore = $trend ? (float) $trend->previous_score : $currentScore;
        $trendDirection = $trend ? $trend->trend_direction : 'Stable';
        
        $triggeredBy = $this->deduceTriggeredBy($model->alert_type, $model->riskScore);

        return new AlertDTO([
            'id' => $model->id,
            'countryName' => $model->country ? $model->country->name : 'Unknown',
            'alertType' => $model->alert_type,
            'severity' => $model->severity,
            'title' => $model->title,
            'description' => $model->description,
            'triggeredBy' => $triggeredBy,
            'currentScore' => $currentScore,
            'previousScore' => $previousScore,
            'trend' => $trendDirection,
            'createdAt' => $model->created_at ? $model->created_at->toIso8601String() : now()->toIso8601String(),
            'status' => $model->status,
        ]);
    }

    private function deduceTriggeredBy(string $type, $score): string
    {
        if (!$score) {
            return 'unknown';
        }

        return match (strtolower(str_replace(' ', '_', $type))) {
            'overall_risk_alert', 'overall_risk' => 'overall_score >= ' . \Illuminate\Support\Facades\Config::get('risk.alert_rules.overall_risk.threshold', 75.0),
            'weather_alert', 'weather' => 'weather_score >= ' . \Illuminate\Support\Facades\Config::get('risk.alert_rules.weather.threshold', 70.0),
            'economic_alert', 'economic' => 'economic_score >= ' . \Illuminate\Support\Facades\Config::get('risk.alert_rules.economic.threshold', 60.0),
            'political_alert', 'political' => 'political_score >= ' . \Illuminate\Support\Facades\Config::get('risk.alert_rules.political.threshold', 65.0),
            'logistics_alert', 'logistics' => 'logistics_score >= ' . \Illuminate\Support\Facades\Config::get('risk.alert_rules.logistics.threshold', 70.0),
            'trend_alert', 'trend' => 'trend_percentage >= ' . \Illuminate\Support\Facades\Config::get('risk.alert_rules.trend.threshold', 15.0),
            'ranking_alert', 'ranking' => 'ranking_drop >= ' . \Illuminate\Support\Facades\Config::get('risk.alert_rules.ranking.threshold', 3),
            'classification_alert', 'classification_change' => 'classification_changed == true',
            default => 'custom_rule',
        };
    }
}
