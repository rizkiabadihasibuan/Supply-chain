<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\NewsCache;
use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GNewsService
{
    /**
     * Fetch news articles for a country.
     * Caches responses in the database 'news_caches' table (valid for 24 hours).
     *
     * @param string $countryCode
     * @param string $countryName
     * @return array
     */
    public function fetchNews(string $countryCode, string $countryName): array
    {
        $countryCode = strtoupper(trim($countryCode));
        $country = Country::where('code', $countryCode)->first();
        
        if (!$country) {
            Log::warning("Country code {$countryCode} not found for news fetching.");
            return [];
        }

        // Check if there are cached news articles created in the last 24 hours
        $cached = NewsCache::where('country_id', $country->id)
            ->where('created_at', '>=', now()->subHours(24))
            ->get();

        if ($cached->isNotEmpty()) {
            return $cached->map(function ($item) {
                return [
                    'title' => $item->title,
                    'description' => $item->description,
                    'source' => $item->source,
                    'url' => $item->url,
                    'published_at' => $item->published_at ? $item->published_at->toIso8601String() : null,
                ];
            })->toArray();
        }

        // Cache expired or missing, fetch from API or mock
        $apiKey = env('GNEWS_API_KEY');
        
        // If API key is not configured, use fallback mock news generator
        if (empty($apiKey)) {
            Log::info("GNEWS_API_KEY not configured. Using mock news fallback for {$countryName}.");
            $articles = $this->getMockNews($countryName);
        } else {
            $query = urlencode("(\"logistics\" OR \"trade\" OR \"shipping\" OR \"economy\" OR \"geopolitical\") AND \"{$countryName}\"");
            $endpoint = "https://gnews.io/api/v4/search?q={$query}&lang=en&max=10&apikey={$apiKey}";
            $startTime = microtime(true);
            $responseStatus = null;

            try {
                $response = Http::withoutVerifying()->timeout(10)->get($endpoint);
                $responseStatus = $response->status();
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                // Log the API call
                $this->logApiCall($endpoint, $responseStatus, $executionTime);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['articles']) && is_array($data['articles'])) {
                        $articles = $this->parseResponse($data['articles']);
                    } else {
                        $articles = $this->getMockNews($countryName);
                    }
                } else {
                    Log::warning("GNews API returned status code {$responseStatus} for country {$countryName}. Falling back to mock news.");
                    $articles = $this->getMockNews($countryName);
                }

            } catch (\Exception $e) {
                $endTime = microtime(true);
                $executionTime = round(($endTime - $startTime) * 1000, 2);

                $this->logApiCall($endpoint, 500, $executionTime);
                Log::error("Failed to connect to GNews API: " . $e->getMessage() . ". Falling back to mock news.");
                $articles = $this->getMockNews($countryName);
            }
        }

        // Delete old cache records for this country
        NewsCache::where('country_id', $country->id)->delete();

        // Insert new cache records
        foreach ($articles as $art) {
            NewsCache::create([
                'country_id' => $country->id,
                'title' => $art['title'],
                'description' => $art['description'],
                'source' => $art['source'],
                'url' => $art['url'],
                'published_at' => $art['published_at'] ? new \DateTime($art['published_at']) : null,
            ]);
        }

        return $articles;
    }

    /**
     * Parse GNews API response into custom news format.
     *
     * @param array $articles
     * @return array
     */
    protected function parseResponse(array $articles): array
    {
        $parsed = [];
        foreach ($articles as $article) {
            $parsed[] = [
                'title' => $article['title'] ?? 'N/A',
                'description' => $article['description'] ?? 'N/A',
                'source' => $article['source']['name'] ?? 'GNews',
                'url' => $article['url'] ?? '#',
                'published_at' => $article['publishedAt'] ?? now()->toIso8601String(),
            ];
        }
        return $parsed;
    }

    /**
     * Log API request details to activity logs.
     *
     * @param string $endpoint
     * @param int $status
     * @param float $executionTime
     */
    protected function logApiCall(string $endpoint, int $status, float $executionTime): void
    {
        try {
            ActivityLog::create([
                'log_type' => 'api_request',
                'description' => "Panggilan GNews API untuk berita intelijen",
                'metadata' => [
                    'api_name' => 'GNews API',
                    'endpoint' => parse_url($endpoint, PHP_URL_SCHEME) . '://' . parse_url($endpoint, PHP_URL_HOST) . parse_url($endpoint, PHP_URL_PATH),
                    'response_status' => $status,
                    'execution_time' => $executionTime,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to write API log for GNews: " . $e->getMessage());
        }
    }

    /**
     * Generate realistic mock news containing keywords for sentiment analysis.
     *
     * @param string $countryName
     * @return array
     */
    protected function getMockNews(string $countryName): array
    {
        // Define some general templates for positive, negative, and neutral stories
        $templates = [
            [
                'title' => "Economic growth accelerates in {$countryName} amid trade success",
                'description' => "New reports show strong recovery and profit expansion in industrial sectors. The market remains stable and secure.",
                'source' => 'Global Trade News',
                'published_at' => now()->subHours(2)->toIso8601String(),
            ],
            [
                'title' => "Logistics disruption in {$countryName} causes major delay and trade loss",
                'description' => "A severe strike and container congestion at ports created a shortage of raw materials. Analysts warn of tariff risk.",
                'source' => 'Logistics Weekly',
                'published_at' => now()->subHours(5)->toIso8601String(),
            ],
            [
                'title' => "Inflation increases in {$countryName} due to global energy crisis",
                'description' => "Rising supply chain disruption and import costs lead to economic decrease. The government fears a major shutdown.",
                'source' => 'Economy Insider',
                'published_at' => now()->subHours(12)->toIso8601String(),
            ],
            [
                'title' => "New shipping corridor improves trade link with {$countryName}",
                'description' => "Infrastructure success and safe maritime agreements strengthen export capacities. Stable prices are expected.",
                'source' => 'Maritime Review',
                'published_at' => now()->subDays(1)->toIso8601String(),
            ],
            [
                'title' => "Port authority in {$countryName} reviews cargo handling regulations",
                'description' => "Meeting was held to discuss digitizing custom declarations and cargo container workflows with international partners.",
                'source' => 'Port Technology',
                'published_at' => now()->subDays(2)->toIso8601String(),
            ],
        ];

        $news = [];
        foreach ($templates as $tmpl) {
            $news[] = [
                'title' => $tmpl['title'],
                'description' => $tmpl['description'],
                'source' => $tmpl['source'],
                'url' => '#',
                'published_at' => $tmpl['published_at'],
            ];
        }

        return $news;
    }
}
