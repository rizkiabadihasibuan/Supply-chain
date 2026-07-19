<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\DashboardAnalyticsRepositoryInterface;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\RiskAlert;
use App\Models\RiskTrend;
use App\Models\Region;
use App\Models\RiskClassification;
use Illuminate\Support\Facades\DB;

class DashboardAnalyticsRepository implements DashboardAnalyticsRepositoryInterface
{
    /**
     * Apply standard filters to a query.
     */
    protected function applyFilters($query, array $filters, string $tableContext = 'risk_scores'): void
    {
        // 1. Country Filter
        if (!empty($filters['country_id'])) {
            if ($tableContext === 'countries') {
                $query->where('id', $filters['country_id']);
            } elseif ($tableContext === 'risk_alerts') {
                $query->where('country_id', $filters['country_id']);
            } else {
                $query->where('country_id', $filters['country_id']);
            }
        }

        // 2. Region / Continent Filters
        if (!empty($filters['region_id'])) {
            if ($tableContext === 'countries') {
                $query->where('region_id', $filters['region_id']);
            } else {
                $query->whereHas('country', function ($q) use ($filters) {
                    $q->where('region_id', $filters['region_id']);
                });
            }
        }

        if (!empty($filters['continent'])) {
            $region = Region::where('name', 'like', '%' . $filters['continent'] . '%')->first();
            if ($region) {
                if ($tableContext === 'countries') {
                    $query->where('region_id', $region->id);
                } else {
                    $query->whereHas('country', function ($q) use ($region) {
                        $q->where('region_id', $region->id);
                    });
                }
            }
        }

        // 3. Subregion Filter
        if (!empty($filters['subregion'])) {
            if ($tableContext === 'countries') {
                $query->where('subregion', $filters['subregion']);
            } else {
                $query->whereHas('country', function ($q) use ($filters) {
                    $q->where('subregion', $filters['subregion']);
                });
            }
        }

        // 4. Classification Filter
        if (!empty($filters['classification_id'])) {
            if ($tableContext === 'risk_scores') {
                $query->where('classification_id', $filters['classification_id']);
            }
        }

        // 5. Date Range Filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $start = $filters['start_date'];
            $end = $filters['end_date'];

            if ($tableContext === 'risk_scores') {
                $query->whereBetween('calculated_at', [$start, $end]);
            } elseif ($tableContext === 'risk_alerts') {
                $query->whereBetween('created_at', [$start, $end]);
            } elseif ($tableContext === 'risk_trends') {
                $query->whereBetween('analyzed_at', [$start, $end]);
            }
        }
    }

    /**
     * Get the latest risk score IDs subquery.
     */
    protected function getLatestScoreIdsQuery()
    {
        return DB::table('risk_scores')
            ->select(DB::raw('MAX(id)'))
            ->groupBy('country_id');
    }

    /**
     * @inheritDoc
     */
    public function getOverviewData(array $filters): array
    {
        // 1. Total Country count
        $countriesQuery = Country::query();
        $this->applyFilters($countriesQuery, $filters, 'countries');
        $totalCountries = $countriesQuery->count();

        // 2. Active Alerts count
        $alertsQuery = RiskAlert::query()->whereIn('status', ['Active', 'New', 'Acknowledged']);
        $this->applyFilters($alertsQuery, $filters, 'risk_alerts');
        $totalActiveAlerts = $alertsQuery->count();

        // 3. Latest scores query for average calculation
        $scoresQuery = RiskScore::query()->whereIn('id', $this->getLatestScoreIdsQuery());
        $this->applyFilters($scoresQuery, $filters, 'risk_scores');
        
        $avgScore = (float) $scoresQuery->avg('final_risk_score');

        // 4. Highest and Lowest risk countries
        $highestScore = (clone $scoresQuery)->with('country')->orderBy('final_risk_score', 'desc')->first();
        $lowestScore = (clone $scoresQuery)->with('country')->orderBy('final_risk_score', 'asc')->first();

        // 5. Average Trend
        $trendsQuery = RiskTrend::query()->whereIn('id', function ($sub) {
            $sub->select(DB::raw('MAX(id)'))->from('risk_trends')->groupBy('country_id');
        });
        $this->applyFilters($trendsQuery, $filters, 'risk_trends');
        $avgTrend = (float) $trendsQuery->avg('change_percentage');

        // 6. Critical Countries count (overall_score >= 80 or level is Critical)
        $criticalCount = (clone $scoresQuery)->where('final_risk_score', '>=', 80.0)->count();

        return [
            'total_countries' => $totalCountries,
            'total_active_alerts' => $totalActiveAlerts,
            'avg_risk_score' => $avgScore,
            'highest_risk_country' => $highestScore ? [
                'name' => $highestScore->country->name,
                'code' => $highestScore->country->code,
                'score' => (float) $highestScore->final_risk_score,
            ] : null,
            'lowest_risk_country' => $lowestScore ? [
                'name' => $lowestScore->country->name,
                'code' => $lowestScore->country->code,
                'score' => (float) $lowestScore->final_risk_score,
            ] : null,
            'avg_trend' => $avgTrend,
            'critical_countries_count' => $criticalCount,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getGlobalSummaryData(array $filters): array
    {
        $scoresQuery = RiskScore::query()->whereIn('id', $this->getLatestScoreIdsQuery());
        $this->applyFilters($scoresQuery, $filters, 'risk_scores');

        $scoresList = $scoresQuery->pluck('final_risk_score')->toArray();

        return [
            'scores' => array_map('floatval', $scoresList),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRiskDistributionData(array $filters): array
    {
        $scoresQuery = RiskScore::query()->whereIn('id', $this->getLatestScoreIdsQuery());
        $this->applyFilters($scoresQuery, $filters, 'risk_scores');

        // Group by classification
        $distribution = $scoresQuery->select('classification_id', DB::raw('COUNT(*) as count'))
            ->groupBy('classification_id')
            ->get()
            ->pluck('count', 'classification_id')
            ->toArray();

        // Get classifications list
        $classifications = RiskClassification::orderBy('min_score', 'asc')->get();

        $result = [];
        foreach ($classifications as $c) {
            $result[$c->name] = $distribution[$c->id] ?? 0;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getTopRiskCountriesData(array $filters): array
    {
        $scoresQuery = RiskScore::query()->whereIn('id', $this->getLatestScoreIdsQuery());
        $this->applyFilters($scoresQuery, $filters, 'risk_scores');

        $topCountries = $scoresQuery->with('country')
            ->orderBy('final_risk_score', 'desc')
            ->limit(10)
            ->get();

        $result = [];
        foreach ($topCountries as $score) {
            $result[] = [
                'name' => $score->country->name,
                'code' => $score->country->code,
                'score' => (float) $score->final_risk_score,
                'level' => $score->risk_level,
            ];
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getLowestRiskCountriesData(array $filters): array
    {
        $scoresQuery = RiskScore::query()->whereIn('id', $this->getLatestScoreIdsQuery());
        $this->applyFilters($scoresQuery, $filters, 'risk_scores');

        $lowestCountries = $scoresQuery->with('country')
            ->orderBy('final_risk_score', 'asc')
            ->limit(10)
            ->get();

        $result = [];
        foreach ($lowestCountries as $score) {
            $result[] = [
                'name' => $score->country->name,
                'code' => $score->country->code,
                'score' => (float) $score->final_risk_score,
                'level' => $score->risk_level,
            ];
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getRiskTrendsData(array $filters): array
    {
        $trendsQuery = RiskTrend::query()->whereIn('id', function ($sub) {
            $sub->select(DB::raw('MAX(id)'))->from('risk_trends')->groupBy('country_id');
        });
        $this->applyFilters($trendsQuery, $filters, 'risk_trends');

        $trendingUp = (clone $trendsQuery)->with('country')
            ->orderBy('change_percentage', 'desc')
            ->limit(10)
            ->get();

        $trendingDown = (clone $trendsQuery)->with('country')
            ->orderBy('change_percentage', 'asc')
            ->limit(10)
            ->get();

        $up = [];
        foreach ($trendingUp as $trend) {
            $up[] = [
                'name' => $trend->country->name,
                'code' => $trend->country->code,
                'change' => (float) $trend->change_percentage,
                'direction' => $trend->trend_direction,
            ];
        }

        $down = [];
        foreach ($trendingDown as $trend) {
            $down[] = [
                'name' => $trend->country->name,
                'code' => $trend->country->code,
                'change' => (float) $trend->change_percentage,
                'direction' => $trend->trend_direction,
            ];
        }

        return [
            'trending_up' => $up,
            'trending_down' => $down,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRiskRankingData(array $filters): array
    {
        $scoresQuery = RiskScore::query()->whereIn('id', $this->getLatestScoreIdsQuery());
        $this->applyFilters($scoresQuery, $filters, 'risk_scores');

        $scores = $scoresQuery->with('country')
            ->orderBy('final_risk_score', 'desc')
            ->get();

        // Fetch latest trends for all countries to avoid N+1 query problem
        $latestTrendIds = DB::table('risk_trends')
            ->select(DB::raw('MAX(id)'))
            ->groupBy('country_id');

        $trends = RiskTrend::whereIn('id', $latestTrendIds)
            ->get()
            ->keyBy('country_id');

        $rankings = [];
        $rank = 1;
        foreach ($scores as $score) {
            $trend = $trends[$score->country_id] ?? null;

            $rankings[] = [
                'rank' => $rank++,
                'name' => $score->country->name,
                'code' => $score->country->code,
                'score' => (float) $score->final_risk_score,
                'level' => $score->risk_level,
                'change' => $trend ? $trend->previous_score - $score->final_risk_score : 0.0,
            ];
        }

        return $rankings;
    }

    /**
     * @inheritDoc
     */
    public function getAlertsSummaryData(array $filters): array
    {
        $alertsQuery = RiskAlert::query();
        $this->applyFilters($alertsQuery, $filters, 'risk_alerts');

        $totalAlert = (clone $alertsQuery)->count();
        $criticalAlert = (clone $alertsQuery)->where('severity', 'Critical')->count();
        $resolvedAlert = (clone $alertsQuery)->where('status', 'Resolved')->count();
        $openAlert = (clone $alertsQuery)->whereIn('status', ['Active', 'New', 'Acknowledged'])->count();

        $byCategory = (clone $alertsQuery)->select('alert_type', DB::raw('COUNT(*) as count'))
            ->groupBy('alert_type')
            ->get()
            ->pluck('count', 'alert_type')
            ->toArray();

        return [
            'total_alert' => $totalAlert,
            'critical_alert' => $criticalAlert,
            'resolved_alert' => $resolvedAlert,
            'open_alert' => $openAlert,
            'by_category' => $byCategory,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getComponentRiskData(string $component, array $filters): array
    {
        $scoresQuery = RiskScore::query()->whereIn('id', $this->getLatestScoreIdsQuery());
        $this->applyFilters($scoresQuery, $filters, 'risk_scores');

        $column = $component . '_score'; // weather_score, economic_score, political_score, logistics_score
        
        $scoresList = $scoresQuery->pluck($column)->toArray();

        $highest = (clone $scoresQuery)->with('country')->orderBy($column, 'desc')->first();
        $lowest = (clone $scoresQuery)->with('country')->orderBy($column, 'asc')->first();

        return [
            'scores' => array_map('floatval', $scoresList),
            'highest' => $highest ? [
                'name' => $highest->country->name,
                'code' => $highest->country->code,
                'score' => (float) $highest->$column,
            ] : null,
            'lowest' => $lowest ? [
                'name' => $lowest->country->name,
                'code' => $lowest->country->code,
                'score' => (float) $lowest->$column,
            ] : null,
        ];
    }
}
