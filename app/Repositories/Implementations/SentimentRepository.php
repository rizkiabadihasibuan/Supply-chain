<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\SentimentHistory;
use App\Models\SentimentMatch;
use App\Models\SentimentResult;
use App\Repositories\Interfaces\SentimentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class SentimentRepository extends BaseRepository implements SentimentRepositoryInterface
{
    /**
     * SentimentRepository constructor.
     *
     * @param SentimentResult $model
     */
    public function __construct(SentimentResult $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->whereHas('newsArticle', function ($q) use ($term) {
            $q->where('title', 'like', '%' . $term . '%');
        })->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['country_id'])) {
            $query->byCountry((int) $filters['country_id']);
        }

        if (!empty($filters['label'])) {
            $query->where('sentiment_label', $filters['label']);
        }

        return $query->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function saveResult(int $newsArticleId, int $countryId, array $sentimentData): SentimentResult
    {
        return $this->model->updateOrCreate(
            ['news_article_id' => $newsArticleId],
            array_merge($sentimentData, [
                'country_id' => $countryId,
                'processed_at' => Carbon::now(),
            ])
        );
    }

    /**
     * @inheritDoc
     */
    public function saveMatch(
        int $resultId,
        string $word,
        string $type,
        float $score,
        int $pos,
        int $freq
    ): SentimentMatch {
        return SentimentMatch::create([
            'sentiment_result_id' => $resultId,
            'matched_word' => $word,
            'word_type' => $type,
            'word_score' => $score,
            'position' => $pos,
            'frequency' => $freq,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function saveHistory(int $countryId, int $resultId, float $avgScore, string $date): SentimentHistory
    {
        return SentimentHistory::updateOrCreate(
            [
                'country_id' => $countryId,
                'sentiment_result_id' => $resultId,
                'recorded_date' => Carbon::parse($date)->toDateString(),
            ],
            [
                'avg_sentiment_score' => $avgScore,
            ]
        );
    }
}
