<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\RestCountriesService;
use App\Services\WorldBankService;
use App\Services\OpenMeteoService;
use App\Services\ExchangeRateService;
use App\Services\RiskScoringEngine;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    protected $restCountriesService;
    protected $worldBankService;
    protected $openMeteoService;
    protected $exchangeRateService;
    protected $riskScoringEngine;

    /**
     * CountryController constructor.
     *
     * @param RestCountriesService $restCountriesService
     * @param WorldBankService $worldBankService
     * @param OpenMeteoService $openMeteoService
     * @param ExchangeRateService $exchangeRateService
     * @param RiskScoringEngine $riskScoringEngine
     */
    public function __construct(
        RestCountriesService $restCountriesService, 
        WorldBankService $worldBankService,
        OpenMeteoService $openMeteoService,
        ExchangeRateService $exchangeRateService,
        RiskScoringEngine $riskScoringEngine
    ) {
        $this->restCountriesService = $restCountriesService;
        $this->worldBankService = $worldBankService;
        $this->openMeteoService = $openMeteoService;
        $this->exchangeRateService = $exchangeRateService;
        $this->riskScoringEngine = $riskScoringEngine;
    }

    /**
     * Retrieve country details from APIs (or Cache).
     *
     * @param string $code
     * @return JsonResponse
     */
    public function detail(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));
        $restData = $this->restCountriesService->fetchByCode($code);
        $bankData = $this->worldBankService->fetchAllMetrics($code);

        if (!$restData && empty($bankData)) {
            return response()->json([
                'success' => false,
                'message' => "Detail negara dengan kode '{$code}' tidak dapat ditemukan atau API eksternal terganggu."
            ], 404);
        }

        $weatherData = null;
        if ($restData && isset($restData['latitude']) && isset($restData['longitude'])) {
            $weatherData = $this->openMeteoService->fetchWeather($code, (float) $restData['latitude'], (float) $restData['longitude']);
        }

        $currencyRate = null;
        if ($restData && isset($restData['currency_code'])) {
            $currencyRate = $this->exchangeRateService->getRateAgainstUsd($restData['currency_code']);
        }

        // Fetch latest risk score from local database
        $country = Country::where('code', $code)->first();
        $latestRisk = $country ? $country->riskScores()->latest()->first() : null;

        $mergedData = array_merge(
            $restData ?? [], 
            $bankData,
            ['weather' => $weatherData],
            ['exchange_rate' => $currencyRate],
            ['latest_risk' => $latestRisk]
        );

        return response()->json([
            'success' => true,
            'data' => $mergedData
        ]);
    }

    /**
     * Sync local country data with REST Countries & World Bank APIs.
     *
     * @param string $code
     * @return JsonResponse
     */
    public function sync(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));
        $country = Country::where('code', $code)->first();

        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => "Negara dengan kode '{$code}' tidak terdaftar di database lokal kami."
            ], 404);
        }

        // Fetch from REST Countries
        $restData = $this->restCountriesService->fetchByCode($code);

        // Fetch from World Bank
        $bankData = $this->worldBankService->fetchAllMetrics($code);

        if (!$restData && empty($bankData)) {
            return response()->json([
                'success' => false,
                'message' => "Gagal mengambil data terbaru dari REST Countries API dan World Bank API."
            ], 502);
        }

        // Sync local record with REST Countries info
        if ($restData) {
            $country->name = $restData['name'] ?? $country->name;
            $country->region = $restData['region'] ?? $country->region;
            $country->language = $restData['language'] ?? $country->language;
            $country->currency_code = $restData['currency_code'] ?? $country->currency_code;
            $country->currency_name = $restData['currency_name'] ?? $country->currency_name;
            $country->latitude = $restData['latitude'] ?? $country->latitude;
            $country->longitude = $restData['longitude'] ?? $country->longitude;
        }

        // Sync weather if coordinates are available
        if ($country->latitude !== null && $country->longitude !== null) {
            $weatherData = $this->openMeteoService->fetchWeather($code, (float) $country->latitude, (float) $country->longitude);
            if ($weatherData) {
                $country->current_weather_temp = $weatherData['temp'] ?? $country->current_weather_temp;
                $country->current_weather_condition = $weatherData['condition'] ?? $country->current_weather_condition;
                $country->current_weather_wind_speed = $weatherData['wind_speed'] ?? $country->current_weather_wind_speed;
                $country->current_weather_precipitation = $weatherData['precipitation'] ?? $country->current_weather_precipitation;
                $country->current_weather_storm_risk = $weatherData['storm_risk'] ?? $country->current_weather_storm_risk;
            }
        }

        // Sync local record with World Bank economic indicators
        if (!empty($bankData)) {
            $country->gdp = $bankData['gdp'] ?? $country->gdp;
            $country->inflation = $bankData['inflation'] ?? $country->inflation;
            $country->population = $bankData['population'] ?? $country->population;
            $country->export_value = $bankData['export'] ?? $country->export_value;
            $country->import_value = $bankData['import'] ?? $country->import_value;
        }

        $country->save();

        // Calculate and save Risk Score via RiskScoringEngine
        $riskScore = $this->riskScoringEngine->calculateRisk($country);

        return response()->json([
            'success' => true,
            'message' => "Data lokal negara '{$country->name}' berhasil disinkronisasikan dengan REST Countries, World Bank, dan Open-Meteo API.",
            'data' => $country->load('riskScores')
        ]);
    }
}
