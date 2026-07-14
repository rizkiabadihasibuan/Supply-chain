<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\RiskScore;
use App\Services\GNewsService;
use App\Services\SentimentAnalyzer;
use App\Services\ExchangeRateService;
use App\Services\RiskScoringEngine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    protected $gnewsService;
    protected $sentimentAnalyzer;
    protected $exchangeRateService;
    protected $riskScoringEngine;

    /**
     * ApiController constructor.
     */
    public function __construct(
        GNewsService $gnewsService,
        SentimentAnalyzer $sentimentAnalyzer,
        ExchangeRateService $exchangeRateService,
        RiskScoringEngine $riskScoringEngine
    ) {
        $this->gnewsService = $gnewsService;
        $this->sentimentAnalyzer = $sentimentAnalyzer;
        $this->exchangeRateService = $exchangeRateService;
        $this->riskScoringEngine = $riskScoringEngine;
    }

    /**
     * GET /api/countries
     * Retrieve all countries with their latest risk scores.
     *
     * @return JsonResponse
     */
    public function countries(): JsonResponse
    {
        $countries = Country::all()->map(function ($country) {
            $latestRisk = $country->riskScores()->latest()->first();
            return [
                'id' => $country->id,
                'code' => $country->code,
                'name' => $country->name,
                'region' => $country->region,
                'language' => $country->language,
                'gdp' => $country->gdp,
                'inflation' => $country->inflation,
                'population' => $country->population,
                'export_value' => $country->export_value,
                'import_value' => $country->import_value,
                'latitude' => $country->latitude,
                'longitude' => $country->longitude,
                'weather_temp' => $country->current_weather_temp,
                'weather_condition' => $country->current_weather_condition,
                'weather_wind_speed' => $country->current_weather_wind_speed,
                'weather_precipitation' => $country->current_weather_precipitation,
                'weather_storm_risk' => $country->current_weather_storm_risk,
                'latest_risk' => $latestRisk ? [
                    'total_risk_score' => (float) $latestRisk->total_risk_score,
                    'risk_level' => $latestRisk->risk_level,
                    'weather_risk_score' => (float) $latestRisk->weather_risk_score,
                    'inflation_risk_score' => (float) $latestRisk->inflation_risk_score,
                    'currency_risk_score' => (float) $latestRisk->currency_risk_score,
                    'political_risk_score' => (float) $latestRisk->political_risk_score,
                    'calculated_at' => $latestRisk->calculated_at,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    /**
     * GET /api/risk
     * Retrieve detailed risk scores for a country.
     * Calculates on-the-fly if not calculated recently.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function risk(Request $request): JsonResponse
    {
        $code = strtoupper(trim($request->query('country', '')));
        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => "Parameter 'country' (kode negara) diperlukan."
            ], 400);
        }

        $country = Country::where('code', $code)->first();
        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => "Negara dengan kode '{$code}' tidak ditemukan di database kami."
            ], 404);
        }

        $latestRisk = $country->riskScores()->latest()->first();
        if (!$latestRisk) {
            $latestRisk = $this->riskScoringEngine->calculateRisk($country);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'country_code' => $country->code,
                'country_name' => $country->name,
                'weather_risk' => (float) $latestRisk->weather_risk_score,
                'inflation_risk' => (float) $latestRisk->inflation_risk_score,
                'currency_risk' => (float) $latestRisk->currency_risk_score,
                'political_risk' => (float) $latestRisk->political_risk_score,
                'total_risk' => (float) $latestRisk->total_risk_score,
                'risk_level' => $latestRisk->risk_level,
                'calculated_at' => $latestRisk->calculated_at,
            ]
        ]);
    }

    /**
     * GET /api/ports
     * Retrieve all ports, optionally filtered by country code.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ports(Request $request): JsonResponse
    {
        $code = strtoupper(trim($request->query('country', '')));
        
        $query = Port::with('country');
        if (!empty($code)) {
            $query->whereHas('country', function ($q) use ($code) {
                $q->where('code', $code);
            });
        }

        $ports = $query->get()->map(function ($port) {
            return [
                'id' => $port->id,
                'port_code' => $port->port_code,
                'name' => $port->name,
                'latitude' => (float) $port->latitude,
                'longitude' => (float) $port->longitude,
                'waiting_time_hours' => $port->waiting_time_hours,
                'congestion_rate' => (float) $port->congestion_rate,
                'country' => [
                    'code' => $port->country->code,
                    'name' => $port->country->name,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $ports
        ]);
    }

    /**
     * GET /api/news
     * Retrieve news articles and analyze their sentiment score.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function news(Request $request): JsonResponse
    {
        $code = strtoupper(trim($request->query('country', '')));
        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => "Parameter 'country' (kode negara) diperlukan."
            ], 400);
        }

        $country = Country::where('code', $code)->first();
        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => "Negara dengan kode '{$code}' tidak ditemukan."
            ], 404);
        }

        $rawNews = $this->gnewsService->fetchNews($country->code, $country->name);
        $analysis = $this->sentimentAnalyzer->analyzeArticles($rawNews);

        return response()->json([
            'success' => true,
            'data' => [
                'country_code' => $country->code,
                'country_name' => $country->name,
                'sentiment_breakdown' => [
                    'positive' => $analysis['positive_percent'],
                    'neutral' => $analysis['neutral_percent'],
                    'negative' => $analysis['negative_percent'],
                ],
                'articles' => $analysis['articles']
            ]
        ]);
    }

    /**
     * GET /api/currency
     * Retrieve exchange rates and simulated 7-day trend for Chart.js.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function currency(Request $request): JsonResponse
    {
        $code = strtoupper(trim($request->query('country', '')));
        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => "Parameter 'country' (kode negara) diperlukan."
            ], 400);
        }

        $country = Country::where('code', $code)->first();
        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => "Negara dengan kode '{$code}' tidak ditemukan."
            ], 404);
        }

        $currencyCode = $country->currency_code ?? 'USD';
        $currentRate = $this->exchangeRateService->getRateAgainstUsd($currencyCode);

        // Fetch volatility from cache (CurrencySeeder)
        $cacheKey = "currency_rate_USD_{$currencyCode}";
        $currencyData = Cache::get($cacheKey);
        $volatility = $currencyData && isset($currencyData['volatility']) ? (float) $currencyData['volatility'] : 1.5;

        // Fallback rate if exchange API is down or rates not found
        if ($currentRate === null) {
            $currentRate = $currencyData && isset($currencyData['rate']) ? (float) $currencyData['rate'] : 1.0;
        }

        // Generate 7-day simulated trend for Chart.js
        $history = [];
        $rateTracker = $currentRate;
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            // Seed a consistent mock fluctuation based on volatility
            // We use the day of month and day of week to make it deterministic but look dynamic
            $seed = (int) now()->subDays($i)->format('d');
            $fluctuationPercent = (($seed % 10) - 5) / 100 * $volatility;
            $rateForDay = $currentRate * (1 + $fluctuationPercent);

            $history[] = [
                'date' => $date,
                'rate' => round($rateForDay, 4),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'currency_code' => $currencyCode,
                'currency_name' => $country->currency_name ?? 'US Dollar',
                'current_rate' => $currentRate,
                'volatility' => $volatility,
                'history' => $history
            ]
        ]);
    }

    /**
     * GET /api/watchlist
     * Retrieve the user's monitored countries with their latest risk scores.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getWatchlist(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $countries = Country::whereIn('id', function ($query) use ($user) {
            $query->select('country_id')
                ->from('watchlists')
                ->where('user_id', $user->id);
        })->get()->map(function ($country) {
            $latestRisk = $country->riskScores()->latest()->first();
            return [
                'id' => $country->id,
                'code' => $country->code,
                'name' => $country->name,
                'region' => $country->region,
                'latest_risk' => $latestRisk ? [
                    'total_risk_score' => (float) $latestRisk->total_risk_score,
                    'risk_level' => $latestRisk->risk_level,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    /**
     * POST /api/watchlist/toggle
     * Toggle a country in the user's watchlist.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleWatchlist(Request $request): JsonResponse
    {
        $user = $request->user();
        $countryCode = strtoupper(trim($request->input('country_code', '')));
        $countryId = $request->input('country_id');

        $query = Country::query();
        if ($countryId) {
            $query->where('id', $countryId);
        } elseif ($countryCode) {
            $query->where('code', $countryCode);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Parameter 'country_id' atau 'country_code' diperlukan."
            ], 400);
        }

        $country = $query->first();
        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => "Negara tidak ditemukan."
            ], 404);
        }

        // Check if exists in watchlists table
        $watchlistItem = \App\Models\Watchlist::where('user_id', $user->id)
            ->where('country_id', $country->id)
            ->first();

        if ($watchlistItem) {
            $watchlistItem->delete();
            $attached = false;
            $message = "Negara '{$country->name}' berhasil dihapus dari daftar pantauan Anda.";
        } else {
            \App\Models\Watchlist::create([
                'user_id' => $user->id,
                'country_id' => $country->id,
            ]);
            $attached = true;
            $message = "Negara '{$country->name}' berhasil ditambahkan ke daftar pantauan Anda.";
        }

        // Keep user JSON column synchronized
        $currentCodes = \App\Models\Watchlist::where('user_id', $user->id)
            ->join('countries', 'watchlists.country_id', '=', 'countries.id')
            ->pluck('countries.code')
            ->toArray();
        
        $user->watchlist_country_codes = $currentCodes;
        $user->save();

        return response()->json([
            'success' => true,
            'attached' => $attached,
            'message' => $message,
            'watchlist' => $currentCodes
        ]);
    }
}
