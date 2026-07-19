<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class AnalyticsDTO implements JsonSerializable
{
    /**
     * AnalyticsDTO constructor.
     *
     * @param array<string, int> $riskDistribution
     * @param int $totalScored
     */
    public function __construct(
        public array $riskDistribution,
        public int $totalScored
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
            riskDistribution: $data['risk_distribution'] ?? [],
            totalScored: (int) ($data['total_scored'] ?? 0)
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
            'risk_distribution' => $this->riskDistribution,
            'total_scored' => $this->totalScored,
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
