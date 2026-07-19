<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

interface CountryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find country by its ISO code (2 or 3 letters).
     *
     * @param string $code
     * @return Country|null
     */
    public function findByCode(string $code): ?Country;

    /**
     * Search countries by name or code.
     *
     * @param string $term
     * @return Collection<int, Country>
     */
    public function search(string $term): Collection;

    /**
     * Filter countries by region, currency or other parameters.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Country>
     */
    public function filter(array $filters): Collection;
}
