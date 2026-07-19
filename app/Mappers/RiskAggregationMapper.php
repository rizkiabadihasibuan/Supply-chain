<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Models\Country;
use App\DTOs\RiskAggregationDTO;
use App\Integrations\OpenMeteo\WeatherDTO;
use App\Integrations\ExchangeRate\ExchangeRateDTO;
use App\Integrations\WorldBank\WorldBankDTO;

class RiskAggregationMapper
{
    /**
     * Map raw data/DTOs to RiskAggregationDTO.
     *
     * @param Country $country
     * @param WeatherDTO|null $weather
     * @param ExchangeRateDTO|null $exchangeRate
     * @param WorldBankDTO|null $economic
     * @param array $news
     * @param array $ports
     * @return RiskAggregationDTO
     */
    public function map(
        Country $country,
        ?WeatherDTO $weather,
        ?ExchangeRateDTO $exchangeRate,
        ?WorldBankDTO $economic,
        array $news,
        array $ports
    ): RiskAggregationDTO {
        $currencyCode = $country->currency ? $country->currency->code : 'USD';
        
        // Resolve rate relative to USD
        $rate = 1.0;
        if ($exchangeRate && isset($exchangeRate->rates[$currencyCode])) {
            $rate = (float) $exchangeRate->rates[$currencyCode];
        }

        // Map News
        $mappedNews = [];
        foreach ($news as $article) {
            $mappedNews[] = [
                'title' => $article->title ?? '',
                'description' => $article->description ?? '',
                'content' => $article->content ?? '',
                'url' => $article->url ?? '',
                'source' => $article->sourceName ?? '',
                'published_at' => $article->publishedAt ?? '',
            ];
        }

        // Map Ports
        $mappedPorts = [];
        foreach ($ports as $port) {
            $mappedPorts[] = [
                'name' => $port->name ?? '',
                'port_code' => $port->portCode ?? '',
                'status' => $port->status ?? 'Unknown',
                'harbor_size' => $port->harborSize ?? 'Unknown',
                'harbor_type' => $port->harborType ?? 'Unknown',
                'services' => $port->services ?? [],
            ];
        }

        return new RiskAggregationDTO([
            'countryName' => $country->name,
            'isoCode' => $country->code,
            'weather' => [
                'temperature' => $weather ? $weather->temperature : null,
                'rainfall' => $weather ? ($weather->dailyForecast['rainfall'][0] ?? null) : null,
                'wind_speed' => $weather ? $weather->windSpeed : null,
                'condition' => $weather ? $this->getConditionFromCode($weather->weatherCode) : 'Unknown',
            ],
            'exchangeRate' => [
                'currency' => $currencyCode,
                'rate' => $rate,
            ],
            'economic' => [
                'gdp' => $economic ? $economic->gdp : null,
                'inflation' => $economic ? $economic->inflation : null,
                'population' => $economic ? $economic->population : $country->population,
            ],
            'news' => $mappedNews,
            'ports' => $mappedPorts,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Map Open-Meteo Weather Code to string condition.
     */
    private function getConditionFromCode(int $code): string
    {
        return match (true) {
            $code === 0 => 'Sunny',
            in_array($code, [1, 2, 3]) => 'Cloudy',
            in_array($code, [45, 48]) => 'Foggy',
            in_array($code, [51, 53, 55, 56, 57]) => 'Drizzle',
            in_array($code, [61, 63, 65, 66, 67]) => 'Rainy',
            in_array($code, [71, 73, 75, 77, 85, 86]) => 'Snowy',
            in_array($code, [80, 81, 82]) => 'Showers',
            in_array($code, [95, 96, 99]) => 'Stormy',
            default => 'Unknown',
        };
    }
}
