<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class WatchlistDTO implements JsonSerializable
{
    /**
     * WatchlistDTO constructor.
     *
     * @param int|null $id
     * @param int $userId
     * @param string $name
     * @param string|null $description
     * @param string $status
     * @param array<int, int> $countryIds
     */
    public function __construct(
        public ?int $id,
        public int $userId,
        public string $name,
        public ?string $description = null,
        public string $status = 'active',
        public array $countryIds = []
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
            userId: (int) ($data['user_id'] ?? 0),
            name: (string) ($data['name'] ?? ''),
            description: $data['description'] ?? null,
            status: (string) ($data['status'] ?? 'active'),
            countryIds: $data['country_ids'] ?? []
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
            'user_id' => $this->userId,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'country_ids' => $this->countryIds,
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
