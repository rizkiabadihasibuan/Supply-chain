<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Currency;
use App\Models\CurrencyCache;
use App\Models\CurrencyHistory;
use Illuminate\Database\Eloquent\Collection;

interface CurrencyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find currency by its code.
     *
     * @param string $code
     * @return Currency|null
     */
    public function findByCode(string $code): ?Currency;

    /**
     * Search currencies.
     *
     * @param string $term
     * @return Collection<int, Currency>
     */
    public function search(string $term): Collection;

    /**
     * Filter currencies.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Currency>
     */
    public function filter(array $filters): Collection;

    /**
     * Get valid rates cache for base currency.
     *
     * @param string $baseCurrency
     * @return CurrencyCache|null
     */
    public function getCache(string $baseCurrency): ?CurrencyCache;

    /**
     * Save/update rates cache.
     *
     * @param string $baseCurrency
     * @param array<string, float> $rates
     * @param int $ttlHours
     * @return CurrencyCache
     */
    public function saveCache(string $baseCurrency, array $rates, int $ttlHours = 24): CurrencyCache;

    /**
     * Save daily exchange rate history.
     *
     * @param int $currencyId
     * @param float $rate
     * @param string $date
     * @return CurrencyHistory
     */
    public function saveHistory(int $currencyId, float $rate, string $date): CurrencyHistory;

    /**
     * Get history rates for a currency.
     *
     * @param int $currencyId
     * @param int $limit
     * @return Collection<int, CurrencyHistory>
     */
    public function getHistory(int $currencyId, int $limit = 30): Collection;
}
