<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\CurrencyCache;
use App\Models\CurrencyHistory;
use Illuminate\Database\Eloquent\Collection;

interface ExchangeRateServiceInterface
{
    /**
     * Get rates for base currency (checks cache, else fetches from ExchangeRate API).
     *
     * @param string $baseCurrency
     * @param bool $forceRefresh
     * @return array<string, float>
     */
    public function getRates(string $baseCurrency, bool $forceRefresh = false): array;

    /**
     * Get exchange rate histories.
     *
     * @param int $currencyId
     * @param int $limit
     * @return Collection<int, CurrencyHistory>
     */
    public function getCurrencyHistory(int $currencyId, int $limit = 30): Collection;
}
