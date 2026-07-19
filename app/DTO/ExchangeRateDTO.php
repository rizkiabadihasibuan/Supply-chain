<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class ExchangeRateDTO implements JsonSerializable
{
    /**
     * ExchangeRateDTO constructor.
     *
     * @param string $baseCurrency
     * @param array<string, float> $rates
     * @param string|null $expiresAt
     */
    public function __construct(
        public string $baseCurrency,
        public array $rates,
        public ?string $expiresAt = null
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
            baseCurrency: strtoupper((string) ($data['base_currency'] ?? 'USD')),
            rates: $data['rates'] ?? $data['rates_data'] ?? [],
            expiresAt: $data['expires_at'] ?? null
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
            'base_currency' => $this->baseCurrency,
            'rates' => $this->rates,
            'expires_at' => $this->expiresAt,
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
