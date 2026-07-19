<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class RiskScoreDTO implements JsonSerializable
{
    /**
     * RiskScoreDTO constructor.
     *
     * @param int|null $id
     * @param int $countryId
     * @param int $classificationId
     * @param float $weatherScore
     * @param float $inflationScore
     * @param float $currencyScore
     * @param float $politicalScore
     * @param float $finalRiskScore
     * @param string $riskLevel
     * @param string|null $calculatedAt
     * @param string|null $sourceVersion
     */
    public function __construct(
        public ?int $id,
        public int $countryId,
        public int $classificationId,
        public float $weatherScore,
        public float $inflationScore,
        public float $currencyScore,
        public float $politicalScore,
        public float $finalRiskScore,
        public string $riskLevel,
        public ?string $calculatedAt = null,
        public ?string $sourceVersion = null
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
            classificationId: (int) ($data['classification_id'] ?? 0),
            weatherScore: (float) ($data['weather_score'] ?? 0.0),
            inflationScore: (float) ($data['inflation_score'] ?? 0.0),
            currencyScore: (float) ($data['currency_score'] ?? 0.0),
            politicalScore: (float) ($data['political_score'] ?? 0.0),
            finalRiskScore: (float) ($data['final_risk_score'] ?? 0.0),
            riskLevel: (string) ($data['risk_level'] ?? 'Medium'),
            calculatedAt: $data['calculated_at'] ?? null,
            sourceVersion: $data['source_version'] ?? null
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
            'classification_id' => $this->classificationId,
            'weather_score' => $this->weatherScore,
            'inflation_score' => $this->inflationScore,
            'currency_score' => $this->currencyScore,
            'political_score' => $this->politicalScore,
            'final_risk_score' => $this->finalRiskScore,
            'risk_level' => $this->riskLevel,
            'calculated_at' => $this->calculatedAt,
            'source_version' => $this->sourceVersion,
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
