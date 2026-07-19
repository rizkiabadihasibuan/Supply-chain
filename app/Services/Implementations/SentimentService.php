<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\NewsArticle;
use App\Models\SentimentResult;
use App\Repositories\Interfaces\DictionaryRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Repositories\Interfaces\SentimentRepositoryInterface;
use App\Services\Contracts\SentimentServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class SentimentService implements SentimentServiceInterface
{
    /**
     * @var SentimentRepositoryInterface
     */
    protected SentimentRepositoryInterface $sentimentRepo;

    /**
     * @var DictionaryRepositoryInterface
     */
    protected DictionaryRepositoryInterface $dictRepo;

    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepo;

    /**
     * SentimentService constructor.
     *
     * @param SentimentRepositoryInterface $sentimentRepo
     * @param DictionaryRepositoryInterface $dictRepo
     * @param NewsRepositoryInterface $newsRepo
     */
    public function __construct(
        SentimentRepositoryInterface $sentimentRepo,
        DictionaryRepositoryInterface $dictRepo,
        NewsRepositoryInterface $newsRepo
    ) {
        $this->sentimentRepo = $sentimentRepo;
        $this->dictRepo = $dictRepo;
        $this->newsRepo = $newsRepo;
    }

    /**
     * @inheritDoc
     */
    public function analyzeArticle(int $newsArticleId, int $dictionaryId): SentimentResult
    {
        $article = $this->newsRepo->findById($newsArticleId);

        if (!$article) {
            throw new \InvalidArgumentException('Article not found.');
        }

        // Clean & Tokenize text content
        $textToAnalyze = sprintf('%s %s', $article->title, $article->content ?? $article->description ?? '');
        $cleanedText = preg_replace('/[^\p{L}\p{N}\s]/u', '', mb_strtolower($textToAnalyze, 'UTF-8'));
        $tokens = array_filter(explode(' ', $cleanedText));
        $tokenCount = count($tokens);

        // Fetch words list from dictionary
        $posWords = $this->dictRepo->getWords($dictionaryId, 'positive');
        $negWords = $this->dictRepo->getWords($dictionaryId, 'negative');

        // Build search maps
        $posMap = [];
        foreach ($posWords as $word) {
            $posMap[$word->word] = $word->score;
        }

        $negMap = [];
        foreach ($negWords as $word) {
            $negMap[$word->word] = $word->score;
        }

        $positiveScore = 0.0;
        $negativeScore = 0.0;
        $matchedWordsList = []; // word => [type, score, frequency, position]

        foreach ($tokens as $pos => $token) {
            if (isset($posMap[$token])) {
                $positiveScore += $posMap[$token];
                if (!isset($matchedWordsList[$token])) {
                    $matchedWordsList[$token] = ['type' => 'positive', 'score' => $posMap[$token], 'frequency' => 0, 'position' => $pos];
                }
                $matchedWordsList[$token]['frequency']++;
            }

            if (isset($negMap[$token])) {
                $negativeScore += $negMap[$token];
                if (!isset($matchedWordsList[$token])) {
                    $matchedWordsList[$token] = ['type' => 'negative', 'score' => $negMap[$token], 'frequency' => 0, 'position' => $pos];
                }
                $matchedWordsList[$token]['frequency']++;
            }
        }

        $totalScore = $positiveScore - $negativeScore;

        if ($totalScore > 0) {
            $label = 'positive';
        } elseif ($totalScore < 0) {
            $label = 'negative';
        } else {
            $label = 'neutral';
        }

        $matchCount = count($matchedWordsList);
        $confidence = $matchCount > 0 ? (float) min(1.00, $matchCount / max(1.00, $tokenCount * 0.1)) : 0.00;

        // Save sentiment calculation
        $result = $this->sentimentRepo->saveResult($newsArticleId, $article->country_id, [
            'positive_score' => $positiveScore,
            'negative_score' => $negativeScore,
            'neutral_score' => $matchCount === 0 ? 1.00 : 0.00,
            'total_score' => $totalScore,
            'sentiment_label' => $label,
            'confidence' => $confidence,
            'analysis_version' => 'lexicon-nlp-v1',
        ]);

        // Save matched keywords details
        foreach ($matchedWordsList as $word => $meta) {
            $this->sentimentRepo->saveMatch($result->id, $word, $meta['type'], $meta['score'], $meta['position'], $meta['frequency']);
        }

        // Update sentiment status back on article
        $this->newsRepo->update($newsArticleId, [
            'sentiment_status' => $label,
        ]);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getCountrySentimentHistory(int $countryId, int $limit = 30): Collection
    {
        return SentimentHistory::where('country_id', $countryId)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
