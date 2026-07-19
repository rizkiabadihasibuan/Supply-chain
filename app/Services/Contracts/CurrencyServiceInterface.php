<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

interface CurrencyServiceInterface
{
    /**
     * Get all currencies.
     *
     * @return Collection<int, Currency>
     */
    public function getCurrencies(): Collection;

    /**
     * Find currency by code.
     *
     * @param string $code
     * @return Currency|null
     */
    public function getCurrencyByCode(string $code): ?Currency;
}
