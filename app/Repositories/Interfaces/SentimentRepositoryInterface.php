<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\SentimentMatch;
use App\Models\SentimentResult;
use App\Models\SentimentHistory;
use Illuminate\Database\Eloquent\Collection;

interface SentimentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search sentiment results by news article title.
     *
     * @param string $term
     * @return Collection<int, SentimentResult>
     */
    public function search(string $term): Collection;

    /**
     * Filter sentiment results.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, SentimentResult>
     */
    public function filter(array $filters): Collection;

    /**
     * Save NLP sentiment calculation results.
     *
     * @param int $newsArticleId
     * @param int $countryId
     * @param array<string, mixed> $sentimentData
     * @return SentimentResult
     */
    public function saveResult(int $newsArticleId, int $countryId, array $sentimentData): SentimentResult;

    /**
     * Save matched sentiment keywords details.
     *
     * @param int $resultId
     * @param string $word
     * @param string $type
     * @param float $score
     * @param int $pos
     * @param int $freq
     * @return SentimentMatch
     */
    public function saveMatch(
        int $resultId,
        string $word,
        string $type,
        float $score,
        int $pos,
        int $freq
    ): SentimentMatch;

    /**
     * Save daily sentiment statistics history.
     *
     * @param int $countryId
     * @param int $resultId
     * @param float $avgScore
     * @param string $date
     * @return SentimentHistory
     */
    public function saveHistory(int $countryId, int $resultId, float $avgScore, string $date): SentimentHistory;
}
