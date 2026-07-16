<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Synchronize currency rates for a country and update local database history.
     *
     * @throws \RuntimeException
     */
    public function syncCountryCurrency(string $code, bool $forceRefresh = false): Country
    {
        $code = strtoupper(trim($code));

        $country = Country::where('code', $code)
            ->orWhere('iso2', $code)
            ->orWhere('iso3', $code)
            ->first();

        if (! $country) {
            throw new \RuntimeException("Negara dengan kode '{$code}' tidak ditemukan di database lokal.");
        }

        $currencyCode = $country->currency_code;
        if (empty($currencyCode)) {
            throw new \RuntimeException("Negara '{$country->name}' tidak memiliki kode mata uang (currency_code).");
        }

        Log::info("Memulai sinkronisasi data nilai tukar mata uang '{$currencyCode}' untuk negara '{$country->name}'.");

        $cacheKey = "currency_sync_{$code}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
            Cache::forget('exchange_rates_usd_base');
        }

        if (! $forceRefresh && Cache::has($cacheKey)) {
            return $country;
        }

        $rate = $this->exchangeRateService->getRateAgainstUsd($currencyCode);

        if ($rate === null) {
            throw new \RuntimeException("Gagal mengambil data kurs terbaru dari Exchange Rate API untuk mata uang '{$currencyCode}'.");
        }

        return DB::transaction(function () use ($country, $currencyCode, $rate, $cacheKey) {
            // Update currency rates
            $country->exchange_rate = $rate;
            $country->exchange_rate_base = 'USD';

            // Maintain rolling 7-day history
            $history = $country->exchange_rate_history ?? [];
            if (! is_array($history)) {
                $history = [];
            }

            $today = now()->format('Y-m-d');

            // Remove duplication for today's date if it already exists
            $history = array_filter($history, function ($item) use ($today) {
                return is_array($item) && isset($item['date']) && $item['date'] !== $today;
            });

            // Prepend today's rate
            array_unshift($history, [
                'date' => $today,
                'rate' => round($rate, 4),
            ]);

            // Slice to keep only the last 7 entries
            $country->exchange_rate_history = array_slice($history, 0, 7);

            $country->save();

            // Set sync cache marker
            Cache::put($cacheKey, true, 86400);

            // Log database audit trail
            $this->logAudit("Berhasil menyelaraskan data kurs mata uang '{$currencyCode}' negara '{$country->name}' dari Exchange Rate API.", [
                'country_id' => $country->id,
                'country_code' => $country->code,
                'currency_code' => $currencyCode,
                'rate' => $rate,
            ]);

            Log::info("Data kurs '{$currencyCode}' untuk negara '{$country->name}' ({$country->code}) berhasil diperbarui.");

            return $country;
        });
    }

    /**
     * Synchronize currency rates for all countries in the database.
     */
    public function syncAllCurrencies(bool $forceRefresh = false): array
    {
        $countries = Country::all();
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($countries as $country) {
            if (empty($country->currency_code)) {
                $results['failed'][] = [
                    'code' => $country->code,
                    'error' => 'Negara tidak memiliki kode mata uang (currency_code).',
                ];

                continue;
            }

            try {
                $this->syncCountryCurrency($country->code, $forceRefresh);
                $results['success'][] = $country->code;
            } catch (\Exception $e) {
                Log::error("Gagal menyinkronkan kurs '{$country->code}' selama syncAllCurrencies: ".$e->getMessage());
                $results['failed'][] = [
                    'code' => $country->code,
                    'error' => $e->getMessage(),
                ];
            }
        }

        Log::info('Sinkronisasi kurs seluruh negara selesai. Sukses: '.count($results['success']).', Gagal: '.count($results['failed']));

        return $results;
    }

    /**
     * Log audit trail.
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
            Log::error('Gagal mencatat log audit mata uang ke database: '.$e->getMessage());
        }
    }
}
