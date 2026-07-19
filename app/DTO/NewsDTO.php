<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class NewsDTO implements JsonSerializable
{
    /**
     * NewsDTO constructor.
     *
     * @param array<int, NewsArticleDTO> $articles
     * @param int $totalArticles
     */
    public function __construct(
        public array $articles,
        public int $totalArticles
    ) {}

    /**
     * Create DTO from array.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $articles = [];
        $rawArticles = $data['articles'] ?? [];

        foreach ($rawArticles as $rawArticle) {
            if ($rawArticle instanceof NewsArticleDTO) {
                $articles[] = $rawArticle;
            } else {
                $articles[] = NewsArticleDTO::fromArray((array) $rawArticle);
            }
        }

        return new self(
            articles: $articles,
            totalArticles: (int) ($data['totalArticles'] ?? count($articles))
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
            'articles' => array_map(fn(NewsArticleDTO $art) => $art->toArray(), $this->articles),
            'totalArticles' => $this->totalArticles,
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
