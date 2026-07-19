<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class CountryDTO implements JsonSerializable
{
    /**
     * CountryDTO constructor.
     *
     * @param int|null $id
     * @param int $regionId
     * @param int $currencyId
     * @param string $code
     * @param string $name
     * @param string|null $subregion
     * @param int|null $population
     * @param float|null $area
     * @param string|null $timezone
     */
    public function __construct(
        public ?int $id,
        public int $regionId,
        public int $currencyId,
        public string $code,
        public string $name,
        public ?string $subregion = null,
        public ?int $population = null,
        public ?float $area = null,
        public ?string $timezone = null
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
            id: isset($data['id']) ? (int) $data['id'] : null,
            regionId: (int) ($data['region_id'] ?? 0),
            currencyId: (int) ($data['currency_id'] ?? 0),
            code: strtoupper((string) ($data['code'] ?? '')),
            name: (string) ($data['name'] ?? ''),
            subregion: $data['subregion'] ?? null,
            population: isset($data['population']) ? (int) $data['population'] : null,
            area: isset($data['area']) ? (float) $data['area'] : null,
            timezone: $data['timezone'] ?? null
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
            'id' => $this->id,
            'region_id' => $this->regionId,
            'currency_id' => $this->currencyId,
            'code' => $this->code,
            'name' => $this->name,
            'subregion' => $this->subregion,
            'population' => $this->population,
            'area' => $this->area,
            'timezone' => $this->timezone,
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
