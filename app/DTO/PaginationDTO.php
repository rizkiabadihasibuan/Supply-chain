<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class PaginationDTO implements JsonSerializable
{
    /**
     * PaginationDTO constructor.
     *
     * @param array<int, mixed> $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param int $lastPage
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $perPage,
        public int $currentPage,
        public int $lastPage
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
            items: $data['items'] ?? [],
            total: (int) ($data['total'] ?? 0),
            perPage: (int) ($data['per_page'] ?? $data['perPage'] ?? 15),
            currentPage: (int) ($data['current_page'] ?? $data['currentPage'] ?? 1),
            lastPage: (int) ($data['last_page'] ?? $data['lastPage'] ?? 1)
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
            'items' => $this->items,
            'total' => $this->total,
            'per_page' => $this->perPage,
            'current_page' => $this->currentPage,
            'last_page' => $this->lastPage,
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
