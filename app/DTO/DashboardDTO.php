<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class DashboardDTO implements JsonSerializable
{
    /**
     * DashboardDTO constructor.
     *
     * @param int $totalCountries
     * @param int $totalPorts
     * @param int $criticalRisks
     * @param int $newsArticlesCount
     */
    public function __construct(
        public int $totalCountries,
        public int $totalPorts,
        public int $criticalRisks,
        public int $newsArticlesCount
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
            totalCountries: (int) ($data['total_countries'] ?? 0),
            totalPorts: (int) ($data['total_ports'] ?? 0),
            criticalRisks: (int) ($data['critical_risks'] ?? 0),
            newsArticlesCount: (int) ($data['news_articles_count'] ?? 0)
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
            'total_countries' => $this->totalCountries,
            'total_ports' => $this->totalPorts,
            'critical_risks' => $this->criticalRisks,
            'news_articles_count' => $this->newsArticlesCount,
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
