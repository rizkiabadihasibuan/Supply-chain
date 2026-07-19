<?php

namespace App\Integrations\OpenMeteo;

class WeatherDTO
{
    public readonly float $latitude;
    public readonly float $longitude;
    public readonly float $temperature;
    public readonly int $weatherCode;
    public readonly string $weatherDescription;
    public readonly float $rain;
    public readonly float $humidity;
    public readonly float $windSpeed;
    public readonly int $rainProbability;
    public readonly float $pressure;
    public readonly string $timezone;
    public readonly array $hourlyForecast;
    public readonly array $dailyForecast;
    public readonly string $timestamp;
    
    public function __construct(array $data)
    {
        $this->latitude = $data['latitude'] ?? 0.0;
        $this->longitude = $data['longitude'] ?? 0.0;
        $this->temperature = $data['temperature'] ?? 0.0;
        $this->weatherCode = $data['weatherCode'] ?? 0;
        $this->weatherDescription = $data['weatherDescription'] ?? 'Cerah (Clear Sky)';
        $this->rain = (float) ($data['rain'] ?? 0.0);
        $this->humidity = $data['humidity'] ?? 0.0;
        $this->windSpeed = $data['windSpeed'] ?? 0.0;
        $this->rainProbability = $data['rainProbability'] ?? 0;
        $this->pressure = $data['pressure'] ?? 0.0;
        $this->timezone = $data['timezone'] ?? 'UTC';
        $this->hourlyForecast = $data['hourlyForecast'] ?? [];
        $this->dailyForecast = $data['dailyForecast'] ?? [];
        $this->timestamp = $data['timestamp'] ?? now()->toIso8601String();
    }
}