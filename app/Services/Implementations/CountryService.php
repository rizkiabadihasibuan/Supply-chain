<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\Country;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Services\Contracts\CountryServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class CountryService implements CountryServiceInterface
{
    /**
     * @var CountryRepositoryInterface
     */
    protected CountryRepositoryInterface $countryRepo;

    /**
     * CountryService constructor.
     *
     * @param CountryRepositoryInterface $countryRepo
     */
    public function __construct(CountryRepositoryInterface $countryRepo)
    {
        $this->countryRepo = $countryRepo;
    }

    /**
     * @inheritDoc
     */
    public function getCountries(): Collection
    {
        return $this->countryRepo->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getCountryById(int $id): ?Country
    {
        return $this->countryRepo->findById($id);
    }

    /**
     * @inheritDoc
     */
    public function getCountryByCode(string $code): ?Country
    {
        return $this->countryRepo->findByCode($code);
    }

    /**
     * @inheritDoc
     */
    public function searchCountries(string $term): Collection
    {
        return $this->countryRepo->search($term);
    }

    /**
     * @inheritDoc
     */
    public function filterCountries(array $filters): Collection
    {
        return $this->countryRepo->filter($filters);
    }
}
