<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class NewsArticleDTO implements JsonSerializable
{
    /**
     * NewsArticleDTO constructor.
     *
     * @param int|null $id
     * @param int|null $countryId
     * @param int|null $sourceId
     * @param int|null $categoryId
     * @param string $title
     * @param string|null $description
     * @param string|null $content
     * @param string $url
     * @param string|null $imageUrl
     * @param string|null $author
     * @param string|null $publishedAt
     * @param string $language
     * @param string $sentimentStatus
     * @param float|null $riskScoreReference
     */
    public function __construct(
        public ?int $id,
        public ?int $countryId,
        public ?int $sourceId,
        public ?int $categoryId,
        public string $title,
        public ?string $description = null,
        public ?string $content = null,
        public string $url = '',
        public ?string $imageUrl = null,
        public ?string $author = null,
        public ?string $publishedAt = null,
        public string $language = 'en',
        public string $sentimentStatus = 'neutral',
        public ?float $riskScoreReference = null
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
            countryId: isset($data['country_id']) ? (int) $data['country_id'] : null,
            sourceId: isset($data['source_id']) ? (int) $data['source_id'] : null,
            categoryId: isset($data['category_id']) ? (int) $data['category_id'] : null,
            title: (string) ($data['title'] ?? ''),
            description: $data['description'] ?? null,
            content: $data['content'] ?? null,
            url: (string) ($data['url'] ?? $data['link'] ?? ''),
            imageUrl: $data['image_url'] ?? $data['image'] ?? null,
            author: $data['author'] ?? $data['source']['name'] ?? null,
            publishedAt: $data['published_at'] ?? $data['publishedAt'] ?? null,
            language: (string) ($data['language'] ?? 'en'),
            sentimentStatus: (string) ($data['sentiment_status'] ?? 'neutral'),
            riskScoreReference: isset($data['risk_score_reference']) ? (float) $data['risk_score_reference'] : null
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
            'source_id' => $this->sourceId,
            'category_id' => $this->categoryId,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'image_url' => $this->imageUrl,
            'author' => $this->author,
            'published_at' => $this->publishedAt,
            'language' => $this->language,
            'sentiment_status' => $this->sentimentStatus,
            'risk_score_reference' => $this->riskScoreReference,
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
