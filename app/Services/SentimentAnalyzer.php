<?php

namespace App\Services;

use App\Models\NegativeWord;
use App\Models\PositiveWord;
use Illuminate\Support\Facades\Cache;

class SentimentAnalyzer
{
    protected ?array $positiveWords = null;

    protected ?array $negativeWords = null;

    /**
     * Load lexicons from database. Caches them for performance.
     */
    protected function loadLexicons(): void
    {
        if ($this->positiveWords !== null && $this->negativeWords !== null) {
            return;
        }

        // Cache lexicon words for 1 hour to avoid DB calls on every analysis
        $this->positiveWords = Cache::remember('lexicon_positive_words', 3600, function () {
            return PositiveWord::pluck('word')->map(fn ($w) => strtolower(trim($w)))->toArray();
        });

        $this->negativeWords = Cache::remember('lexicon_negative_words', 3600, function () {
            return NegativeWord::pluck('word')->map(fn ($w) => strtolower(trim($w)))->toArray();
        });
    }

    /**
     * Analyze a single piece of text and return its sentiment metrics.
     */
    public function analyzeText(string $text): array
    {
        $this->loadLexicons();

        // Convert to lowercase and remove punctuation
        $cleanText = strtolower(trim($text));
        $cleanText = preg_replace('/[^\w\s]/u', '', $cleanText);
        $words = preg_split('/\s+/', $cleanText, -1, PREG_SPLIT_NO_EMPTY);

        $positiveScore = 0;
        $negativeScore = 0;
        $matchedPositive = [];
        $matchedNegative = [];

        foreach ($words as $word) {
            if (in_array($word, $this->positiveWords)) {
                $positiveScore++;
                $matchedPositive[] = $word;
            }
            if (in_array($word, $this->negativeWords)) {
                $negativeScore++;
                $matchedNegative[] = $word;
            }
        }

        if ($positiveScore > $negativeScore) {
            $sentiment = 'Positive';
        } elseif ($negativeScore > $positiveScore) {
            $sentiment = 'Negative';
        } else {
            $sentiment = 'Neutral';
        }

        return [
            'sentiment' => $sentiment,
            'positive_score' => $positiveScore,
            'negative_score' => $negativeScore,
            'matched_positive' => array_unique($matchedPositive),
            'matched_negative' => array_unique($matchedNegative),
        ];
    }

    /**
     * Analyze an array of articles and calculate overall percentages.
     */
    public function analyzeArticles(array $articles): array
    {
        if (empty($articles)) {
            return [
                'positive_percent' => 0.0,
                'neutral_percent' => 0.0,
                'negative_percent' => 0.0,
                'positive_count' => 0,
                'neutral_count' => 0,
                'negative_count' => 0,
                'dominant_sentiment' => 'Neutral',
                'articles' => [],
            ];
        }

        $posCount = 0;
        $neuCount = 0;
        $negCount = 0;
        $analyzedArticles = [];

        foreach ($articles as $article) {
            // Combine title and description for a richer sentiment context
            $textToAnalyze = ($article['title'] ?? '').' '.($article['description'] ?? '');
            $analysis = $this->analyzeText($textToAnalyze);

            if ($analysis['sentiment'] === 'Positive') {
                $posCount++;
            } elseif ($analysis['sentiment'] === 'Negative') {
                $negCount++;
            } else {
                $neuCount++;
            }

            $analyzedArticles[] = array_merge($article, [
                'sentiment' => $analysis['sentiment'],
                'positive_score' => $analysis['positive_score'],
                'negative_score' => $analysis['negative_score'],
            ]);
        }

        $total = count($articles);
        $posPercent = round(($posCount / $total) * 100, 2);
        $neuPercent = round(($neuCount / $total) * 100, 2);
        $negPercent = round(($negCount / $total) * 100, 2);

        // Determine dominant sentiment
        if ($posCount > $negCount && $posCount > $neuCount) {
            $dominant = 'Positive';
        } elseif ($negCount > $posCount && $negCount > $neuCount) {
            $dominant = 'Negative';
        } else {
            $dominant = 'Neutral';
        }

        return [
            'positive_percent' => $posPercent,
            'neutral_percent' => $neuPercent,
            'negative_percent' => $negPercent,
            'positive_count' => $posCount,
            'neutral_count' => $neuCount,
            'negative_count' => $negCount,
            'dominant_sentiment' => $dominant,
            'articles' => $analyzedArticles,
        ];
    }
}
