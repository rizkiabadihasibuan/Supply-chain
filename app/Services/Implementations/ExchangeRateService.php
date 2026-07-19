<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\Interfaces\ApiLogRepositoryInterface;
use App\Repositories\Interfaces\CurrencyRepositoryInterface;
use App\Services\Contracts\ExchangeRateServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService implements ExchangeRateServiceInterface
{
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
     *
     * @param CurrencyRepositoryInterface $currencyRepo
     * @param ApiLogRepositoryInterface $apiLogRepo
     */
    public function __construct(
        CurrencyRepositoryInterface $currencyRepo,
        ApiLogRepositoryInterface $apiLogRepo
    ) {
        $this->currencyRepo = $currencyRepo;
        $this->apiLogRepo = $apiLogRepo;
    }

    /**
     * @inheritDoc
     */
    public function getRates(string $baseCurrency, bool $forceRefresh = false): array
    {
        $base = strtoupper(trim($baseCurrency));

        if (!$forceRefresh) {
            $cached = $this->currencyRepo->getCache($base);
            if ($cached) {
                return $cached->rates_data;
            }
        }

        $endpoint = 'https://open.er-api.com/v6/latest/' . $base;

        $startTime = microtime(true);
        $statusCode = null;
        $isSuccess = false;
        $errorMessage = null;

        try {
            $response = Http::timeout(10)->get($endpoint);
            $statusCode = $response->status();
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);

            if ($response->successful()) {
                $isSuccess = true;
                $data = $response->json();
                $rates = $data['rates'] ?? [];

                if (!empty($rates)) {
                    $this->currencyRepo->saveCache($base, $rates);
                    $this->apiLogRepo->log('ExchangeRate API', $endpoint, 'GET', $statusCode, true, null, $durationMs);

                    return $rates;
                }
            }

            $errorMessage = $response->body();
            $this->apiLogRepo->log('ExchangeRate API', $endpoint, 'GET', $statusCode, false, $errorMessage, $durationMs);
        } catch (\Exception $e) {
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);
            $errorMessage = $e->getMessage();
            $this->apiLogRepo->log('ExchangeRate API', $endpoint, 'GET', $statusCode, false, $errorMessage, $durationMs);
            Log::error('ExchangeRate API Error: ' . $errorMessage);
        }

        // Fallback response: load latest expired/valid rates from DB cache
        $fallback = CurrencyCache::where('base_currency', $base)->first();
        return $fallback ? $fallback->rates_data : ['USD' => 1.0];
    }

    /**
     * @inheritDoc
     */
    public function getCurrencyHistory(int $currencyId, int $limit = 30): Collection
    {
        return $this->currencyRepo->getHistory($currencyId, $limit);
    }
}
