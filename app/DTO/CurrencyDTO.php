<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class CurrencyDTO implements JsonSerializable
{
    /**
     * CurrencyDTO constructor.
     *
     * @param int|null $id
     * @param string $code
     * @param string $name
     * @param string|null $symbol
     */
    public function __construct(
        public ?int $id,
        public string $code,
        public string $name,
        public ?string $symbol = null
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
            code: strtoupper((string) ($data['code'] ?? '')),
            name: (string) ($data['name'] ?? ''),
            symbol: $data['symbol'] ?? null
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
            'code' => $this->code,
            'name' => $this->name,
            'symbol' => $this->symbol,
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
