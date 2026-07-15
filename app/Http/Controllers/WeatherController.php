<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\WeatherService;
use App\Services\RiskScoringEngine;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    protected $weatherService;
    protected $riskScoringEngine;

    public function __construct(WeatherService $weatherService, ?RiskScoringEngine $riskScoringEngine = null)
    {
        $this->weatherService = $weatherService;
        $this->riskScoringEngine = $riskScoringEngine;
    }

    /**
     * Sync local weather data with Open Meteo API.
     *
     * @param string $code
     * @return JsonResponse
     */
    public function sync(string $code): JsonResponse
    {
        $code = strtoupper(trim($code));

        try {
            $country = $this->weatherService->syncWeather($code, true);

            // Re-calculate Risk Score using RiskScoringEngine
            if ($this->riskScoringEngine) {
                $this->riskScoringEngine->calculateRisk($country);
            }

            return response()->json([
                'success' => true,
                'message' => "Data cuaca negara '{$country->name}' berhasil diperbarui dari Open Meteo API.",
                'data' => $country->load('riskScores')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Gagal melakukan sinkronisasi cuaca: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync all local countries' weather data with Open Meteo API.
     *
     * @return JsonResponse
     */
    public function syncAll(): JsonResponse
    {
        try {
            $results = $this->weatherService->syncAllWeather(true);

            // Re-calculate risk score for successfully synced countries
            $syncedCountries = Country::whereIn('code', $results['success'])->get();
            foreach ($syncedCountries as $country) {
                if ($this->riskScoringEngine) {
                    $this->riskScoringEngine->calculateRisk($country);
                }
            }

            $successCount = count($results['success']);
            $failedCount = count($results['failed']);

            return response()->json([
                'success' => true,
                'message' => "Sinkronisasi cuaca seluruh negara selesai. Sukses: {$successCount}, Gagal: {$failedCount}.",
                'data' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Gagal melakukan sinkronisasi cuaca massal: " . $e->getMessage()
            ], 500);
        }
    }
}
