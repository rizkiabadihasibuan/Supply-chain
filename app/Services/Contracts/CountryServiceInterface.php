<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

interface CountryServiceInterface
{
    /**
     * Get list of all countries.
     *
     * @return Collection<int, Country>
     */
    public function getCountries(): Collection;

    /**
     * Get specific country details by its ID.
     *
     * @param int $id
     * @return Country|null
     */
    public function getCountryById(int $id): ?Country;

    /**
     * Get specific country details by its ISO code.
     *
     * @param string $code
     * @return Country|null
     */
    public function getCountryByCode(string $code): ?Country;

    /**
     * Search countries by matching query terms.
     *
     * @param string $term
     * @return Collection<int, Country>
     */
    public function searchCountries(string $term): Collection;

    /**
     * Filter countries.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Country>
     */
    public function filterCountries(array $filters): Collection;
}
