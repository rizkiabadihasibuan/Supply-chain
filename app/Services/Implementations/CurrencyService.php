<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\Currency;
use App\Repositories\Interfaces\CurrencyRepositoryInterface;
use App\Services\Contracts\CurrencyServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class CurrencyService implements CurrencyServiceInterface
{
    /**
     * @var CurrencyRepositoryInterface
     */
    protected CurrencyRepositoryInterface $currencyRepo;

    /**
     * CurrencyService constructor.
     *
     * @param CurrencyRepositoryInterface $currencyRepo
     */
    public function __construct(CurrencyRepositoryInterface $currencyRepo)
    {
        $this->currencyRepo = $currencyRepo;
    }

    /**
     * @inheritDoc
     */
    public function getCurrencies(): Collection
    {
        return $this->currencyRepo->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getCurrencyByCode(string $code): ?Currency
    {
        return $this->currencyRepo->findByCode($code);
    }
}
