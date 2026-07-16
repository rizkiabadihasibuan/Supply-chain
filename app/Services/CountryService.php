<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CountryService
{
    /**
     * Cache duration in seconds (24 hours)
     */
    protected const CACHE_DURATION = 86400;

    /**
     * Synchronize a specific country by its ISO2 or ISO3 code.
     *
     * @throws Exception
     */
    public function syncCountry(string $code, bool $forceRefresh = false): Country
    {
        $code = strtoupper(trim($code));
        $cacheKey = "rest_countries_service_{$code}";

        Log::info("Memulai sinkronisasi data negara '{$code}' dari REST Countries API.");

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        $country = Country::where('code', $code)
            ->orWhere('iso2', $code)
            ->orWhere('iso3', $code)
            ->first();

        if (! $forceRefresh && Cache::has($cacheKey) && $country && $country->latitude !== null) {
            return $country;
        }

        $startTime = microtime(true);
        $responseStatus = null;
        $endpoint = "https://restcountries.com/v3.1/alpha/{$code}";

        // Check cache or call API
        $rawData = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($endpoint, $code, $startTime, &$responseStatus) {
            try {
                Log::debug("Memanggil API REST Countries: {$endpoint}");

                // HTTP Client with timeout of 10s and retry on failure (2 times with 100ms delay)
                $response = Http::withoutVerifying()->timeout(10)->retry(2, 100)->get($endpoint);
                $responseStatus = $response->status();
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                $this->logApiCall($endpoint, $responseStatus, $executionTime);

                if (! $response->successful()) {
                    Log::warning("REST Countries API returned status {$responseStatus} for country '{$code}'");
                    throw new RuntimeException("REST Countries API returned error status: {$responseStatus}");
                }

                $json = $response->json();

                // Validate response format
                $this->validateResponse($json);

                return $json;

            } catch (Exception $e) {
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);
                $status = $responseStatus ?? 500;

                $this->logApiCall($endpoint, $status, $executionTime);
                Log::error("Gagal mengambil data dari REST Countries API untuk '{$code}': ".$e->getMessage());
                throw $e;
            }
        });

        // Parse response
        $parsedData = $this->parseResponse($rawData[0], $code);

        // Save to database inside a transaction
        return DB::transaction(function () use ($parsedData, $code) {
            $country = Country::where('code', $code)
                ->orWhere('iso2', $code)
                ->orWhere('iso3', $code)
                ->first();

            if (! $country) {
                // If country is not in database, we create it using code as key
                $country = new Country;
                $country->code = strlen($code) === 2 ? $code : ($parsedData['iso2'] ?? $code);
            }

            // Sync REST Countries specific fields
            $country->name = $parsedData['name'];
            $country->iso2 = $parsedData['iso2'];
            $country->iso3 = $parsedData['iso3'];
            $country->flag_url = $parsedData['flag_url'];
            $country->currency_code = $parsedData['currency_code'];
            $country->currency_name = $parsedData['currency_name'];
            $country->currency_symbol = $parsedData['currency_symbol'];
            $country->region = $parsedData['region'];
            $country->subregion = $parsedData['subregion'];
            $country->capital = $parsedData['capital'];
            $country->language = $parsedData['language'];
            $country->latitude = $parsedData['latitude'];
            $country->longitude = $parsedData['longitude'];
            $country->population = $parsedData['population'];
            $country->area = $parsedData['area'];
            $country->timezone = $parsedData['timezone'];

            $country->save();

            // Log database audit
            $this->logAudit("Berhasil menyelaraskan data negara '{$country->name}' dari REST Countries API.", [
                'country_id' => $country->id,
                'country_code' => $country->code,
                'fields_updated' => array_keys($parsedData),
            ]);

            Log::info("Data negara '{$country->name}' ({$country->code}) berhasil disinkronisasi ke database.");

            return $country;
        });
    }

    /**
     * Synchronize all registered countries in the database.
     */
    public function syncAllCountries(bool $forceRefresh = false): array
    {
        $countries = Country::all();
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($countries as $country) {
            try {
                $this->syncCountry($country->code, $forceRefresh);
                $results['success'][] = $country->code;
            } catch (Exception $e) {
                Log::error("Gagal menyinkronkan negara '{$country->code}' selama syncAll: ".$e->getMessage());
                $results['failed'][] = [
                    'code' => $country->code,
                    'error' => $e->getMessage(),
                ];
            }
        }

        Log::info('Sinkronisasi semua negara selesai. Sukses: '.count($results['success']).', Gagal: '.count($results['failed']));

        return $results;
    }

    /**
     * Validate REST Countries API response.
     *
     * @param  mixed  $response
     *
     * @throws RuntimeException
     */
    protected function validateResponse($response): void
    {
        if (! is_array($response) || empty($response)) {
            throw new RuntimeException('Respon dari REST Countries API kosong atau berformat salah.');
        }

        $countryData = $response[0];

        if (empty($countryData['name']['common'])) {
            throw new RuntimeException("Respon API tidak memiliki nama negara ('name.common').");
        }
    }

    /**
     * Parse REST Countries API raw response data.
     */
    protected function parseResponse(array $data, string $requestedCode): array
    {
        // Extract currency code, name, and symbol
        $currencyCode = null;
        $currencyName = null;
        $currencySymbol = null;

        if (! empty($data['currencies']) && is_array($data['currencies'])) {
            $currencyCode = array_key_first($data['currencies']);
            if ($currencyCode) {
                $currencyName = $data['currencies'][$currencyCode]['name'] ?? null;
                $currencySymbol = $data['currencies'][$currencyCode]['symbol'] ?? null;
            }
        }

        // Extract languages
        $languages = null;
        if (! empty($data['languages']) && is_array($data['languages'])) {
            $languages = implode(', ', array_values($data['languages']));
        }

        // Extract coordinates (latitude and longitude)
        $latitude = null;
        $longitude = null;
        if (! empty($data['capitalInfo']['latlng']) && is_array($data['capitalInfo']['latlng']) && count($data['capitalInfo']['latlng']) >= 2) {
            $latitude = $data['capitalInfo']['latlng'][0];
            $longitude = $data['capitalInfo']['latlng'][1];
        } elseif (! empty($data['latlng']) && is_array($data['latlng']) && count($data['latlng']) >= 2) {
            $latitude = $data['latlng'][0];
            $longitude = $data['latlng'][1];
        }

        // Extract capital
        $capital = null;
        if (! empty($data['capital']) && is_array($data['capital'])) {
            $capital = $data['capital'][0];
        }

        // Extract flag URL
        $flagUrl = $data['flags']['png'] ?? ($data['flags']['svg'] ?? null);

        // Extract timezones
        $timezone = null;
        if (! empty($data['timezones']) && is_array($data['timezones'])) {
            $timezone = implode(', ', $data['timezones']);
        }

        return [
            'name' => $data['name']['common'],
            'iso2' => $data['cca2'] ?? (strlen($requestedCode) === 2 ? $requestedCode : null),
            'iso3' => $data['cca3'] ?? (strlen($requestedCode) === 3 ? $requestedCode : null),
            'flag_url' => $flagUrl,
            'currency_code' => $currencyCode,
            'currency_name' => $currencyName,
            'currency_symbol' => $currencySymbol,
            'region' => $data['region'] ?? null,
            'subregion' => $data['subregion'] ?? null,
            'capital' => $capital,
            'language' => $languages,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'population' => $data['population'] ?? null,
            'area' => $data['area'] ?? null,
            'timezone' => $timezone,
        ];
    }

    /**
     * Log API request to database activity_logs.
     */
    protected function logApiCall(string $endpoint, int $status, float $executionTime): void
    {
        try {
            ActivityLog::create([
                'log_type' => 'api_request',
                'description' => 'Panggilan REST Countries API untuk kode '.basename($endpoint),
                'metadata' => [
                    'api_name' => 'REST Countries API',
                    'endpoint' => $endpoint,
                    'response_status' => $status,
                    'execution_time_ms' => $executionTime,
                ],
            ]);
        } catch (Exception $e) {
            Log::error('Gagal mencatat log panggilan API ke database: '.$e->getMessage());
        }
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
        } catch (Exception $e) {
            Log::error('Gagal mencatat log audit ke database: '.$e->getMessage());
        }
    }
}
