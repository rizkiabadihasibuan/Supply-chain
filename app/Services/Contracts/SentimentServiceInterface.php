<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\SentimentResult;
use Illuminate\Database\Eloquent\Collection;

interface SentimentServiceInterface
{
    /**
     * Run lexicon-based sentiment analysis on a news article.
     * Matches positive and negative words from dictionary.
     *
     * @param int $newsArticleId
     * @param int $dictionaryId
     * @return SentimentResult
     */
    public function analyzeArticle(int $newsArticleId, int $dictionaryId): SentimentResult;

    /**
     * Get sentiment history charts metrics.
     *
     * @param int $countryId
     * @param int $limit
     * @return Collection
     */
    public function getCountrySentimentHistory(int $countryId, int $limit = 30): Collection;
}
