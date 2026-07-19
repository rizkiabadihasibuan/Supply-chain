<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class SentimentDTO implements JsonSerializable
{
    /**
     * SentimentDTO constructor.
     *
     * @param int|null $id
     * @param int $newsArticleId
     * @param int $countryId
     * @param float $positiveScore
     * @param float $negativeScore
     * @param float $neutralScore
     * @param float $totalScore
     * @param string $sentimentLabel
     * @param float $confidence
     * @param string|null $processedAt
     * @param string|null $analysisVersion
     */
    public function __construct(
        public ?int $id,
        public int $newsArticleId,
        public int $countryId,
        public float $positiveScore,
        public float $negativeScore,
        public float $neutralScore,
        public float $totalScore,
        public string $sentimentLabel,
        public float $confidence,
        public ?string $processedAt = null,
        public ?string $analysisVersion = null
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
            newsArticleId: (int) ($data['news_article_id'] ?? 0),
            countryId: (int) ($data['country_id'] ?? 0),
            positiveScore: (float) ($data['positive_score'] ?? 0.0),
            negativeScore: (float) ($data['negative_score'] ?? 0.0),
            neutralScore: (float) ($data['neutral_score'] ?? 0.0),
            totalScore: (float) ($data['total_score'] ?? 0.0),
            sentimentLabel: (string) ($data['sentiment_label'] ?? 'neutral'),
            confidence: (float) ($data['confidence'] ?? 0.0),
            processedAt: $data['processed_at'] ?? null,
            analysisVersion: $data['analysis_version'] ?? null
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
            'news_article_id' => $this->newsArticleId,
            'country_id' => $this->countryId,
            'positive_score' => $this->positiveScore,
            'negative_score' => $this->negativeScore,
            'neutral_score' => $this->neutralScore,
            'total_score' => $this->totalScore,
            'sentiment_label' => $this->sentimentLabel,
            'confidence' => $this->confidence,
            'processed_at' => $this->processedAt,
            'analysis_version' => $this->analysisVersion,
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
