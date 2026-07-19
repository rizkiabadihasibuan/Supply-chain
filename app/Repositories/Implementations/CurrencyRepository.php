<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Currency;
use App\Models\CurrencyCache;
use App\Models\CurrencyHistory;
use App\Repositories\Interfaces\CurrencyRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRepository extends BaseRepository implements CurrencyRepositoryInterface
{
    /**
     * CurrencyRepository constructor.
     *
     * @param Currency $model
     */
    public function __construct(Currency $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByCode(string $code): ?Currency
    {
        return $this->model->where('code', strtoupper(trim($code)))->first();
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('name', 'like', '%' . $term . '%')
            ->orWhere('code', 'like', '%' . $term . '%')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        return $this->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getCache(string $baseCurrency): ?CurrencyCache
    {
        return CurrencyCache::valid()
            ->byCurrency($baseCurrency)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function saveCache(string $baseCurrency, array $rates, int $ttlHours = 24): CurrencyCache
    {
        return CurrencyCache::updateOrCreate(
            ['base_currency' => strtoupper(trim($baseCurrency))],
            [
                'rates_data' => $rates,
                'expires_at' => Carbon::now()->addHours($ttlHours),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function saveHistory(int $currencyId, float $rate, string $date): CurrencyHistory
    {
        return CurrencyHistory::updateOrCreate(
            [
                'currency_id' => $currencyId,
                'recorded_date' => Carbon::parse($date)->toDateString(),
            ],
            [
                'rate_vs_usd' => $rate,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getHistory(int $currencyId, int $limit = 30): Collection
    {
        return CurrencyHistory::byCurrency($currencyId)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
