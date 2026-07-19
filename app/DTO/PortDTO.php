<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class PortDTO implements JsonSerializable
{
    /**
     * PortDTO constructor.
     *
     * @param int|null $id
     * @param int $countryId
     * @param int $categoryId
     * @param string $code
     * @param string $name
     * @param float $latitude
     * @param float $longitude
     * @param string|null $size
     * @param string|null $type
     * @param string|null $harborType
     */
    public function __construct(
        public ?int $id,
        public int $countryId,
        public int $categoryId,
        public string $code,
        public string $name,
        public float $latitude,
        public float $longitude,
        public ?string $size = null,
        public ?string $type = null,
        public ?string $harborType = null
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
            countryId: (int) ($data['country_id'] ?? 0),
            categoryId: (int) ($data['category_id'] ?? 0),
            code: strtoupper((string) ($data['code'] ?? '')),
            name: (string) ($data['name'] ?? ''),
            latitude: (float) ($data['latitude'] ?? 0.0),
            longitude: (float) ($data['longitude'] ?? 0.0),
            size: $data['size'] ?? null,
            type: $data['type'] ?? null,
            harborType: $data['harbor_type'] ?? null
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
            'country_id' => $this->countryId,
            'category_id' => $this->categoryId,
            'code' => $this->code,
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'size' => $this->size,
            'type' => $this->type,
            'harbor_type' => $this->harborType,
        ];
    }

    /**
     * Convert DTO to JSON.
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
