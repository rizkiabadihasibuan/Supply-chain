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

        $positiveWordsObj = $this->dictRepo->getWords($dictionaryId, 'positive');
        $negativeWordsObj = $this->dictRepo->getWords($dictionaryId, 'negative');

        $positiveWords = [];
        foreach ($positiveWordsObj as $pw) {
            $positiveWords[] = strtolower($pw->word);
        }

        $negativeWords = [];
        foreach ($negativeWordsObj as $nw) {
            $negativeWords[] = strtolower($nw->word);
        }

        $words = $tokens;
        $positiveScore = 0;
        $negativeScore = 0;
        $matchedWordsList = []; // Untuk kebutuhan simpan ke DB log

        foreach ($words as $pos => $word) {
            $word = strtolower($word);
            if (in_array($word, $positiveWords)) {
                $positiveScore++;
                $matchedWordsList[$word] = ['type' => 'positive', 'score' => 1.0, 'position' => $pos, 'frequency' => ($matchedWordsList[$word]['frequency'] ?? 0) + 1];
            }
            if (in_array($word, $negativeWords)) {
                $negativeScore++;
                $matchedWordsList[$word] = ['type' => 'negative', 'score' => 1.0, 'position' => $pos, 'frequency' => ($matchedWordsList[$word]['frequency'] ?? 0) + 1];
            }
        }

        $sentiment = $positiveScore > $negativeScore ? "Positive" : "Negative";
        
        $label = strtolower($sentiment);
        $totalScore = $positiveScore - $negativeScore;
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
