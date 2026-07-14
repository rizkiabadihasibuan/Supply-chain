<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Models\RiskScore;
use App\Services\SentimentAnalyzer;
use App\Services\RiskScoringEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SentimentAndRiskTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $country;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & lexicons
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'LexiconSeeder']);

        $analystRole = Role::where('name', 'Analyst')->first();
        $this->user = User::factory()->create([
            'role_id' => $analystRole->id
        ]);

        // Create country
        $this->country = Country::create([
            'code' => 'DE',
            'name' => 'Germany',
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'region' => 'Europe',
            'language' => 'German',
            'gdp' => 4400000000000,
            'inflation' => 2.1,
            'population' => 84000000,
            'current_weather_temp' => 18.5,
            'current_weather_condition' => 'Partly Cloudy',
            'current_weather_storm_risk' => 10.0,
        ]);

        // Setup currency cache volatility for Germany (EUR)
        Cache::forever('currency_rate_USD_EUR', ['rate' => 0.92, 'volatility' => 1.25]);

        // Configure environment variable for GNews API Key in tests
        putenv('GNEWS_API_KEY=testkey');

        // Clear cache
        Cache::forget('lexicon_positive_words');
        Cache::forget('lexicon_negative_words');
        Cache::forget('gnews_articles_DE');
    }

    public function test_sentiment_analyzer_evaluates_text(): void
    {
        $analyzer = app(SentimentAnalyzer::class);

        // Text with: "increase" (positive) & "inflation", "war", "decrease" (negative)
        $text = "Inflation increase while exports decrease due to war.";
        $result = $analyzer->analyzeText($text);

        $this->assertEquals('Negative', $result['sentiment']);
        $this->assertEquals(1, $result['positive_score']);
        $this->assertEquals(3, $result['negative_score']);
    }

    /**
     * Test SentimentAnalyzer handles multiple articles.
     */
    public function test_sentiment_analyzer_aggregates_articles(): void
    {
        $analyzer = app(SentimentAnalyzer::class);

        $articles = [
            [
                'title' => 'Economic growth and success', // positive: growth, success
                'description' => 'Great profit and stable recovery.', // positive: profit, stable, recovery
            ],
            [
                'title' => 'Crisis and war disruption', // negative: crisis, war, disruption
                'description' => 'Serious delay and major shortage.', // negative: delay, shortage
            ],
            [
                'title' => 'Average market movement', // neutral
                'description' => 'No major news.',
            ]
        ];

        $results = $analyzer->analyzeArticles($articles);

        $this->assertEquals(33.33, $results['positive_percent']);
        $this->assertEquals(33.33, $results['negative_percent']);
        $this->assertEquals(33.33, $results['neutral_percent']);
        $this->assertEquals(1, $results['positive_count']);
        $this->assertEquals(1, $results['negative_count']);
        $this->assertEquals(1, $results['neutral_count']);
    }

    /**
     * Test RiskScoringEngine computes correct scores.
     */
    public function test_risk_scoring_engine_computes_score(): void
    {
        // Fake GNews to return 1 negative article, 1 positive, 1 neutral. Negative percent = 33.33%
        $mockNews = [
            [
                'title' => 'Economic success',
                'description' => 'Great growth.',
            ],
            [
                'title' => 'Geopolitical war',
                'description' => 'Disaster and disruption.',
            ],
            [
                'title' => 'Regular trade update',
                'description' => 'No keywords.',
            ]
        ];

        // Mock GNews response
        Http::fake([
            'https://gnews.io/api/v4/search*' => Http::response(['articles' => $mockNews], 200)
        ]);

        // Volatility for EUR = 1.25 -> currencyRisk = 1.25 * 35 = 43.75
        // Weather Risk = 10.0 (from country current_weather_storm_risk)
        // Inflation = 2.1 (from country inflation) -> <= 3.0 -> (2.1/3.0)*25 = 17.5
        // Political Risk = 33.33 (Negative percent)
        // Expected weighted score: (0.30 * 10.0) + (0.20 * 17.5) + (0.10 * 43.75) + (0.40 * 33.33)
        // = 3.0 + 3.5 + 4.375 + 13.332 = 24.21%
        
        $engine = app(RiskScoringEngine::class);
        $riskScore = $engine->calculateRisk($this->country);

        $this->assertNotNull($riskScore);
        $this->assertEquals($this->country->id, $riskScore->country_id);
        $this->assertEquals(10.0, $riskScore->weather_risk_score);
        $this->assertEquals(17.5, $riskScore->inflation_risk_score);
        $this->assertEquals(43.75, $riskScore->currency_risk_score);
        $this->assertEquals(33.33, $riskScore->political_risk_score);
        $this->assertEquals(24.21, $riskScore->total_risk_score);
        $this->assertEquals('Low', $riskScore->risk_level);

        $this->assertDatabaseHas('risk_scores', [
            'country_id' => $this->country->id,
            'risk_level' => 'Low',
        ]);
    }

    /**
     * Test country sync controller triggers risk score calculation.
     */
    public function test_country_sync_triggers_risk_calculation(): void
    {
        // Mock all external services
        $mockCountryData = [
            [
                'name' => ['common' => 'Germany'],
                'region' => 'Europe',
                'population' => 84000000,
                'currencies' => [
                    'EUR' => ['name' => 'Euro']
                ],
                'languages' => [
                    'deu' => 'German'
                ],
                'flags' => [
                    'svg' => 'https://flagcdn.com/de.svg'
                ],
                'capital' => ['Berlin'],
                'latlng' => [51.0, 9.0],
                'capitalInfo' => [
                    'latlng' => [52.52, 13.40]
                ]
            ]
        ];

        Http::fake([
            'https://restcountries.com/v3.1/alpha/DE' => Http::response($mockCountryData, 200),
            'http://api.worldbank.org/v2/country/de/indicator/*' => Http::response([[], [['date' => '2023', 'value' => 84000000]]], 200),
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current' => [
                    'temperature_2m' => 15.0,
                    'precipitation' => 0.0,
                    'weather_code' => 0,
                    'wind_speed_10m' => 5.0,
                    'wind_gusts_10m' => 10.0,
                ]
            ], 200),
            'https://open.er-api.com/v6/latest/USD' => Http::response([
                'rates' => [
                    'EUR' => 0.92
                ]
            ], 200),
            'https://gnews.io/api/v4/search*' => Http::response([
                'articles' => [
                    ['title' => 'Germany trade crisis', 'description' => 'Delay and collapse of economy.']
                ]
            ], 200)
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('countries.sync', ['code' => 'DE']));

        $response->assertStatus(200);

        // Check that a risk score was generated
        $this->assertDatabaseHas('risk_scores', [
            'country_id' => $this->country->id,
        ]);
        
        $latestScore = RiskScore::where('country_id', $this->country->id)->latest()->first();
        $this->assertNotNull($latestScore);
        // Crisis (negative), delay (negative), collapse (negative), economy (neutral/not seeded)
        // 100% negative news -> political_risk_score = 100.0
        $this->assertEquals(100.0, $latestScore->political_risk_score);
    }

    /**
     * Test GNewsService database caching.
     */
    public function test_gnews_service_database_caching(): void
    {
        $mockNews = [
            [
                'title' => 'First news article',
                'description' => 'Disruption and delay.',
                'source' => ['name' => 'Reuters'],
                'url' => 'https://example.com/1',
                'publishedAt' => '2026-07-14T05:00:00Z',
            ]
        ];

        Http::fake([
            'https://gnews.io/api/v4/search*' => Http::response(['articles' => $mockNews], 200)
        ]);

        $service = app(\App\Services\GNewsService::class);

        // 1. First fetch -> calls HTTP API
        $articles = $service->fetchNews('DE', 'Germany');
        Http::assertSentCount(1);
        $this->assertCount(1, $articles);
        $this->assertEquals('First news article', $articles[0]['title']);

        // Assert records exist in news_caches table
        $this->assertDatabaseHas('news_caches', [
            'country_id' => $this->country->id,
            'title' => 'First news article',
        ]);

        // 2. Second fetch -> loads from news_caches DB table directly without HTTP call
        $articlesCached = $service->fetchNews('DE', 'Germany');
        Http::assertSentCount(1); // Still 1
        $this->assertCount(1, $articlesCached);
        $this->assertEquals('First news article', $articlesCached[0]['title']);
    }
}
