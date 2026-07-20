<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Integrations\ExchangeRate\ExchangeRateApiClient;
use App\Repositories\Interfaces\ApiLogRepositoryInterface;
use App\Repositories\Interfaces\CurrencyRepositoryInterface;
use App\Services\Contracts\ExchangeRateServiceInterface;
use App\Models\CurrencyCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ExchangeRateService implements ExchangeRateServiceInterface
{
    /**
     * @var ExchangeRateApiClient
     */
    protected ExchangeRateApiClient $apiClient;

    /**
     * @var CurrencyRepositoryInterface
     */
    protected CurrencyRepositoryInterface $currencyRepo;

    /**
     * @var ApiLogRepositoryInterface
     */
    protected ApiLogRepositoryInterface $apiLogRepo;

    /**
     * ExchangeRateService constructor.
     */
    public function __construct(
        ExchangeRateApiClient $apiClient,
        CurrencyRepositoryInterface $currencyRepo,
        ApiLogRepositoryInterface $apiLogRepo
    ) {
        $this->apiClient = $apiClient;
        $this->currencyRepo = $currencyRepo;
        $this->apiLogRepo = $apiLogRepo;
    }

    /**
     * @inheritDoc
     */
    public function getRates(string $baseCurrency, bool $forceRefresh = false): array
    {
        $base = strtoupper(trim($baseCurrency));
        $startTime = microtime(true);

        // 1. Always attempt live API fetch first for real-time rates
        try {
            $data = $this->apiClient->getLatest($base);
            $rates = $data['rates'] ?? [];
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);

            if (!empty($rates)) {
                $this->currencyRepo->saveCache($base, $rates);
                $this->apiLogRepo->log('ExchangeRate API', "https://open.er-api.com/v6/latest/{$base}", 'GET', 200, true, null, $durationMs);

                return $rates;
            }
        } catch (\Throwable $e) {
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);
            $this->apiLogRepo->log('ExchangeRate API', "https://open.er-api.com/v6/latest/{$base}", 'GET', 500, false, $e->getMessage(), $durationMs);
            Log::error('ExchangeRate API Error via ExchangeRateApiClient: ' . $e->getMessage());
        }

        // 2. Fallback to cache if network API is offline
        $cached = $this->currencyRepo->getCache($base);
        if ($cached && !empty($cached->rates_data)) {
            return $cached->rates_data;
        }

        // 3. Fallback database cache or hardcoded defaults
        $fallback = CurrencyCache::where('base_currency', $base)->first();
        return $fallback ? $fallback->rates_data : [
            'USD' => 1.0,
            'IDR' => 16245.0,
            'EUR' => 0.92,
            'JPY' => 157.0,
            'SGD' => 1.34,
            'CNY' => 7.25,
            'GBP' => 0.79,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCurrencyHistory(int $currencyId, int $limit = 30): Collection
    {
        return $this->currencyRepo->getHistory($currencyId, $limit);
    }
}
