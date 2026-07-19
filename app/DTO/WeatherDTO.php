<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class WeatherDTO implements JsonSerializable
{
    /**
     * WeatherDTO constructor.
     *
     * @param float $latitude
     * @param float $longitude
     * @param float $temperature
     * @param float $windSpeed
     * @param int $humidity
     * @param string|null $recordedAt
     */
    public function __construct(
        public float $latitude,
        public float $longitude,
        public float $temperature,
        public float $windSpeed,
        public int $humidity,
        public ?string $recordedAt = null
    ) {}

    /**
     * Create DTO from array.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            latitude: (float) ($data['latitude'] ?? 0.0),
            longitude: (float) ($data['longitude'] ?? 0.0),
            temperature: (float) ($data['temperature'] ?? $data['current']['temperature_2m'] ?? 0.0),
            windSpeed: (float) ($data['wind_speed'] ?? $data['current']['wind_speed_10m'] ?? 0.0),
            humidity: (int) ($data['humidity'] ?? $data['current']['relative_humidity_2m'] ?? 0),
            recordedAt: $data['recorded_at'] ?? $data['current']['time'] ?? null
        );
    }

    /**
     * Convert DTO to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'temperature' => $this->temperature,
            'wind_speed' => $this->windSpeed,
            'humidity' => $this->humidity,
            'recorded_at' => $this->recordedAt,
        ];
    }

    /**
     * Convert DTO to JSON string.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
