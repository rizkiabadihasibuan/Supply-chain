<?php

namespace App\Services;

use App\Integrations\RestCountries\RestCountriesApiClient;
use App\Integrations\RestCountries\CountryMapper;
use App\Integrations\RestCountries\CountryCacheManager;
use App\Integrations\RestCountries\CountryDTO;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Models\Region;
use App\Models\Currency;
use App\Models\Language;
use App\Models\CountryCoordinate;
use App\Models\CountryFlag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountryService
{
    protected $apiClient;
    protected $mapper;
    protected $cacheManager;
    protected $countryRepository;

    public function __construct(
        RestCountriesApiClient $apiClient,
        CountryMapper $mapper,
        CountryCacheManager $cacheManager,
        CountryRepositoryInterface $countryRepository
    ) {
        $this->apiClient = $apiClient;
        $this->mapper = $mapper;
        $this->cacheManager = $cacheManager;
        $this->countryRepository = $countryRepository;
    }

    /**
     * Search countries from database
     */
    public function searchCountries(string $term)
    {
        return $this->countryRepository->search($term);
    }

    /**
     * Get all countries from database
     */
    public function getAllCountries(): array
    {
        $dtos = $this->getAllCountriesDTOs();
        return array_map([$this, 'formatCountry'], $dtos);
    }

    /**
     * Get country by ID
     */
    public function getCountryById(int $id)
    {
        return $this->countryRepository->findById($id);
    }

    /**
     * Get country by code
     */
    public function getCountryByCode(string $code)
    {
        return $this->countryRepository->findByCode($code);
    }

    /**
     * Create a country record
     */
    public function createCountry(array $data)
    {
        return $this->countryRepository->create($data);
    }

    /**
     * Update a country record
     */
    public function updateCountry(int $id, array $data)
    {
        $this->countryRepository->update($id, $data);
        return $this->countryRepository->findById($id);
    }

    /**
     * Delete a country record
     */
    public function deleteCountry(int $id)
    {
        return $this->countryRepository->delete($id);
    }

    /**
     * Get countries by region/continent
     */
    public function getCountriesByRegion(int $regionId)
    {
        return $this->countryRepository->filter(['region_id' => $regionId]);
    }

    /**
     * Sync countries from REST Countries API to Database
     */
    public function syncCountries(bool $forceRefresh = false): array
    {
        $startTime = microtime(true);
        Log::info("Starting countries synchronization from REST Countries API...");

        try {
            $rawCountries = $this->cacheManager->getCachedAll(function () {
                return $this->apiClient->all();
            }, $forceRefresh);

            if (empty($rawCountries)) {
                Log::warning("REST Countries API returned empty response.");
                return ['success' => false, 'count' => 0, 'message' => 'Empty response from API'];
            }

            $dtos = $this->mapper->mapMany($rawCountries);
            $syncedCount = 0;

            foreach ($dtos as $dto) {
                // Validate required fields
                if (empty($dto->iso2) || empty($dto->commonName)) {
                    continue;
                }

                DB::transaction(function () use ($dto, &$syncedCount) {
                    // 1. Get or Create Region
                    $regionName = !empty($dto->region) ? $dto->region : 'Unknown';
                    $region = Region::firstOrCreate(['name' => $regionName]);

                    // 2. Get or Create Currency
                    $currencyCode = !empty($dto->currencyCode) ? $dto->currencyCode : 'XXX';
                    $currencyName = !empty($dto->currencyName) ? $dto->currencyName : 'Unknown Currency';
                    $currencySymbol = !empty($dto->currencySymbol) ? $dto->currencySymbol : null;
                    
                    $currency = Currency::firstOrCreate(
                        ['code' => strtoupper($currencyCode)],
                        ['name' => $currencyName, 'symbol' => $currencySymbol]
                    );

                    // 3. Update or Create Country
                    $country = $this->countryRepository->findByCode($dto->iso2);

                    $timezone = !empty($dto->timezones) ? $dto->timezones[0] : 'UTC';

                    $countryData = [
                        'region_id' => $region->id,
                        'currency_id' => $currency->id,
                        'code' => strtoupper($dto->iso2),
                        'name' => $dto->commonName,
                        'subregion' => $dto->subregion ?: null,
                        'population' => $dto->population ?: null,
                        'area' => $dto->area ?: null,
                        'timezone' => $timezone,
                    ];

                    if ($country) {
                        $this->countryRepository->update($country->id, $countryData);
                        $country = $this->countryRepository->findById($country->id);
                    } else {
                        $country = $this->countryRepository->create($countryData);
                    }

                    // 4. Update or Create Coordinate
                    CountryCoordinate::updateOrCreate(
                        ['country_id' => $country->id],
                        ['latitude' => $dto->latitude, 'longitude' => $dto->longitude]
                    );

                    // 5. Update or Create Flag
                    CountryFlag::updateOrCreate(
                        ['country_id' => $country->id],
                        ['flag_url' => $dto->flagUrl ?: null, 'svg_path' => $dto->svgPath ?: null]
                    );

                    // 6. Sync Languages
                    $languageIds = [];
                    foreach ($dto->languages as $langCode => $langName) {
                        $language = Language::firstOrCreate(
                            ['code' => strtolower($langCode)],
                            ['name' => $langName]
                        );
                        $languageIds[] = $language->id;
                    }
                    $country->languages()->sync($languageIds);

                    $syncedCount++;
                });
            }

            $duration = (microtime(true) - $startTime) * 1000;
            Log::info("Successfully synchronized {$syncedCount} countries in " . round($duration, 2) . "ms");

            return [
                'success' => true,
                'count' => $syncedCount,
                'duration' => $duration,
                'message' => "Successfully synchronized {$syncedCount} countries."
            ];

        } catch (\Exception $e) {
            Log::error("Failed to synchronize countries: " . $e->getMessage(), [
                'exception' => $e
            ]);
            throw $e;
        }
    }

    protected function getAllCountriesDTOs(bool $forceRefresh = false): array
    {
        $cacheKey = 'rest_countries_all_dtos';
        $backupKey = 'rest_countries_all_dtos_backup';
        $ttl = 7 * 24 * 60 * 60; // 7 days

        if (!$forceRefresh && \Illuminate\Support\Facades\Cache::has($cacheKey)) {
            return \Illuminate\Support\Facades\Cache::get($cacheKey);
        }

        try {
            $rawCountries = $this->apiClient->all();
            $dtos = $this->mapper->mapMany($rawCountries);

            \Illuminate\Support\Facades\Cache::put($cacheKey, $dtos, now()->addSeconds($ttl));
            \Illuminate\Support\Facades\Cache::forever($backupKey, $dtos);
            return $dtos;
        } catch (\Throwable $e) {
            Log::warning("REST Countries API failed: {$e->getMessage()}. Falling back to backup cache...");
            if (\Illuminate\Support\Facades\Cache::has($backupKey)) {
                return \Illuminate\Support\Facades\Cache::get($backupKey);
            }
            throw $e;
        }
    }

    public function getCountryByName(string $countryName): ?array
    {
        $dto = $this->findCountry($countryName);
        return $dto ? $this->formatCountry($dto) : null;
    }

    public function getCountryByISO2(string $code): ?array
    {
        $dto = $this->findCountry($code);
        return $dto ? $this->formatCountry($dto) : null;
    }

    public function getCountryByISO3(string $code): ?array
    {
        $dto = $this->findCountry($code);
        return $dto ? $this->formatCountry($dto) : null;
    }

    public function searchCountry(string $keyword): array
    {
        $dtos = $this->getAllCountriesDTOs();
        $cleanKw = strtolower(trim($keyword));
        $matches = [];

        foreach ($dtos as $dto) {
            if (str_contains(strtolower($dto->commonName), $cleanKw) ||
                str_contains(strtolower($dto->officialName), $cleanKw) ||
                str_contains(strtolower($dto->iso2), $cleanKw) ||
                str_contains(strtolower($dto->iso3), $cleanKw)) {
                $matches[] = $this->formatCountry($dto);
            }
        }

        return $matches;
    }

    public function getCoordinates(string $country): ?array
    {
        $dto = $this->findCountry($country);
        return $dto ? [
            'latitude' => $dto->latitude,
            'longitude' => $dto->longitude
        ] : null;
    }

    public function getCurrency(string $country): ?array
    {
        $dto = $this->findCountry($country);
        return $dto ? [
            'currency' => $dto->currencyCode,
            'currency_symbol' => $dto->currencySymbol
        ] : null;
    }

    public function getCountrySummary(string $country): ?array
    {
        return $this->getCountryByName($country);
    }

    public function findCountry(string $identifier): ?CountryDTO
    {
        $all = $this->getAllCountriesDTOs();
        $cleanId = strtolower(trim($identifier));

        foreach ($all as $dto) {
            if (strtolower($dto->commonName) === $cleanId ||
                strtolower($dto->officialName) === $cleanId ||
                strtolower($dto->iso2) === $cleanId ||
                strtolower($dto->iso3) === $cleanId) {
                return $dto;
            }
        }

        // Try loose check
        foreach ($all as $dto) {
            if (str_contains(strtolower($dto->commonName), $cleanId) ||
                str_contains(strtolower($dto->officialName), $cleanId)) {
                return $dto;
            }
        }

        return null;
    }

    public function formatCountry(CountryDTO $dto): array
    {
        return [
            'country' => $dto->commonName,
            'official_name' => $dto->officialName,
            'iso2' => $dto->iso2,
            'iso3' => $dto->iso3,
            'currency' => $dto->currencyCode,
            'currency_symbol' => $dto->currencySymbol,
            'capital' => $dto->capital,
            'region' => $dto->region,
            'subregion' => $dto->subregion,
            'population' => $dto->population,
            'area' => $dto->area,
            'latitude' => $dto->latitude,
            'longitude' => $dto->longitude,
            'timezone' => !empty($dto->timezones) ? $dto->timezones[0] : 'UTC',
            'flag' => $dto->flagUrl,
            'updated_at' => now()->toIso8601String(),
        ];
    }
}
