<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WorldBankService
{
    /**
     * World Bank Indicators mapping.
     */
    protected const INDICATORS = [
        'gdp' => 'NY.GDP.MKTP.CD',
        'inflation' => 'FP.CPI.TOTL.ZG',
        'population' => 'SP.POP.TOTL',
        'export' => 'NE.EXP.GNFS.CD',
        'import' => 'NE.IMP.GNFS.CD',
    ];

    /**
     * Fetch a specific economic indicator for a country from World Bank API.
     * With 24-hour caching and request logging.
     *
     * @param  string  $countryCode  (ISO-2)
     * @param  string  $metric  ('gdp', 'inflation', 'population', 'export', 'import')
     */
    public function fetchIndicator(string $countryCode, string $metric): ?array
    {
        $countryCode = strtolower(trim($countryCode));
        $metric = strtolower(trim($metric));

        if (! array_key_exists($metric, self::INDICATORS)) {
            Log::error("Invalid economic metric requested: {$metric}");

            return null;
        }

        $indicatorCode = self::INDICATORS[$metric];
        $cacheKey = "world_bank_{$countryCode}_{$metric}";

        return Cache::remember($cacheKey, 86400, function () use ($countryCode, $indicatorCode) {
            // Get data for past 5 years to find the most recent non-null entry
            $endpoint = "http://api.worldbank.org/v2/country/{$countryCode}/indicator/{$indicatorCode}?date=2019:2024&format=json";
            $startTime = microtime(true);
            $responseStatus = null;

            try {
                $response = Http::withoutVerifying()->timeout(10)->retry(3, 200)->get($endpoint);
                $responseStatus = $response->status();
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                // Log the API request
                $this->logApiCall($endpoint, $responseStatus, $executionTime);

                if ($response->successful()) {
                    $data = $response->json();

                    // World Bank API returns an array: [0 => metadata, 1 => records]
                    if (is_array($data) && count($data) >= 2 && is_array($data[1])) {
                        return $this->parseRecentValue($data[1]);
                    }
                }

                Log::warning("World Bank API returned status code {$responseStatus} for country {$countryCode}, indicator {$indicatorCode}.");

                return null;

            } catch (\Exception $e) {
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                $this->logApiCall($endpoint, 500, $executionTime);
                Log::error('Failed to connect to World Bank API: '.$e->getMessage());

                return null;
            }
        });
    }

    /**
     * Fetch all 5 key economic metrics for a country.
     */
    public function fetchAllMetrics(string $countryCode): array
    {
        $results = [];
        foreach (array_keys(self::INDICATORS) as $metric) {
            $data = $this->fetchIndicator($countryCode, $metric);
            $results[$metric] = $data['value'] ?? null;
            $results["{$metric}_year"] = $data['year'] ?? null;
        }

        return $results;
    }

    /**
     * Log API request details to activity logs.
     */
    protected function logApiCall(string $endpoint, int $status, float $executionTime): void
    {
        try {
            ActivityLog::create([
                'log_type' => 'api_request',
                'description' => 'Panggilan World Bank API untuk endpoint '.parse_url($endpoint, PHP_URL_PATH),
                'metadata' => [
                    'api_name' => 'World Bank API',
                    'endpoint' => $endpoint,
                    'response_status' => $status,
                    'execution_time' => $executionTime,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to write API log: '.$e->getMessage());
        }
    }

    /**
     * Parse World Bank JSON records to get the most recent non-null value and its year.
     */
    protected function parseRecentValue(array $records): ?array
    {
        // Loop from the first record (usually the most recent year)
        foreach ($records as $record) {
            if (isset($record['value']) && $record['value'] !== null) {
                return [
                    'year' => (int) $record['date'],
                    'value' => $record['value'],
                ];
            }
        }

        return null;
    }

    /**
     * Synchronize economic indicators for a country using World Bank API.
     *
     * @throws \RuntimeException
     */
    public function syncCountryEconomicData(string $code, bool $forceRefresh = false): Country
    {
        $code = strtoupper(trim($code));

        $country = Country::where('code', $code)
            ->orWhere('iso2', $code)
            ->orWhere('iso3', $code)
            ->first();

        if (! $country) {
            throw new \RuntimeException("Negara dengan kode '{$code}' tidak ditemukan di database lokal.");
        }

        // World Bank API needs ISO2
        $apiCode = $country->iso2 ?? (strlen($country->code) === 2 ? $country->code : $code);

        Log::info("Memulai sinkronisasi data ekonomi untuk negara '{$country->name}' ({$apiCode}).");

        $cacheKey = "world_bank_sync_{$code}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
            foreach (array_keys(self::INDICATORS) as $metric) {
                Cache::forget('world_bank_'.strtolower($apiCode)."_{$metric}");
            }
        }

        if (! $forceRefresh && Cache::has($cacheKey)) {
            return $country;
        }

        $metrics = [];
        foreach (array_keys(self::INDICATORS) as $metric) {
            try {
                $data = $this->fetchIndicator($apiCode, $metric);
                $metrics[$metric] = $data['value'] ?? null;
            } catch (\Exception $e) {
                Log::error("Gagal mengambil metrik {$metric} untuk {$apiCode}: ".$e->getMessage());
                $metrics[$metric] = null;
            }
        }

        // Validate that we got at least some data
        $allNull = true;
        foreach ($metrics as $val) {
            if ($val !== null) {
                $allNull = false;
                break;
            }
        }

        if ($allNull) {
            throw new \RuntimeException("Gagal mengambil data terbaru dari World Bank API untuk negara '{$country->name}' (Semua metrik bernilai null).");
        }

        // Save to database inside a transaction
        return DB::transaction(function () use ($country, $metrics, $cacheKey) {
            $country->gdp = $metrics['gdp'] ?? $country->gdp;
            $country->inflation = $metrics['inflation'] ?? $country->inflation;
            $country->population = $metrics['population'] ?? $country->population;
            $country->export_value = $metrics['export'] ?? $country->export_value;
            $country->import_value = $metrics['import'] ?? $country->import_value;
            $country->save();

            // Set sync cache marker
            Cache::put($cacheKey, true, 86400);

            // Log database audit trail
            $this->logAudit("Berhasil menyelaraskan data ekonomi negara '{$country->name}' dari World Bank API.", [
                'country_id' => $country->id,
                'country_code' => $country->code,
                'metrics_updated' => array_keys(array_filter($metrics, fn ($v) => $v !== null)),
            ]);

            Log::info("Data ekonomi negara '{$country->name}' ({$country->code}) berhasil diperbarui.");

            return $country;
        });
    }

    /**
     * Synchronize economic indicators for all countries.
     */
    public function syncAllEconomicData(bool $forceRefresh = false): array
    {
        $countries = Country::all();
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($countries as $country) {
            try {
                $this->syncCountryEconomicData($country->code, $forceRefresh);
                $results['success'][] = $country->code;
            } catch (\Exception $e) {
                Log::error("Gagal menyinkronkan data ekonomi '{$country->code}' selama syncAll: ".$e->getMessage());
                $results['failed'][] = [
                    'code' => $country->code,
                    'error' => $e->getMessage(),
                ];
            }
        }

        Log::info('Sinkronisasi data ekonomi seluruh negara selesai. Sukses: '.count($results['success']).', Gagal: '.count($results['failed']));

        return $results;
    }

    /**
     * Log audit trail to activity_logs.
     */
    protected function logAudit(string $description, ?array $metadata = null): void
    {
        try {
            ActivityLog::create([
                'log_type' => 'audit',
                'description' => $description,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mencatat log audit ke database: '.$e->getMessage());
        }
    }
}
