<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class RiskDTO implements JsonSerializable
{
    /**
     * RiskDTO constructor.
     *
     * @param int|null $id
     * @param int $riskScoreId
     * @param int $categoryId
     * @param string $indicatorName
     * @param float $rawValue
     * @param float $weight
     * @param float $weightedScore
     */
    public function __construct(
        public ?int $id,
        public int $riskScoreId,
        public int $categoryId,
        public string $indicatorName,
        public float $rawValue,
        public float $weight,
        public float $weightedScore
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
            riskScoreId: (int) ($data['risk_score_id'] ?? 0),
            categoryId: (int) ($data['category_id'] ?? 0),
            indicatorName: (string) ($data['indicator_name'] ?? ''),
            rawValue: (float) ($data['raw_value'] ?? 0.0),
            weight: (float) ($data['weight'] ?? 0.0),
            weightedScore: (float) ($data['weighted_score'] ?? 0.0)
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
            'risk_score_id' => $this->riskScoreId,
            'category_id' => $this->categoryId,
            'indicator_name' => $this->indicatorName,
            'raw_value' => $this->rawValue,
            'weight' => $this->weight,
            'weighted_score' => $this->weightedScore,
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
