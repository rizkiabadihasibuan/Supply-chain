<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Services\NewsService;
use App\Jobs\SyncNewsJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_fetch_news_from_api_and_return_dtos(): void
    {
        Http::fake([
            'https://gnews.io/api/v4/*' => Http::response([
                'totalArticles' => 1,
                'articles' => [
                    [
                        'title' => 'Supply Chain Crisis Hits Global Trade',
                        'description' => 'A major disruption has hit global trade due to port congestion.',
                        'content' => 'Full article content about supply chain congestion...',
                        'url' => 'https://example.com/news/supply-chain-crisis',
                        'image' => 'https://example.com/images/news1.jpg',
                        'publishedAt' => '2026-07-19T00:00:00Z',
                        'source' => [
                            'name' => 'Global Trade News',
                            'url' => 'https://example.com'
                        ]
                    ]
                ]
            ], 200)
        ]);

        $service = app(NewsService::class);
        $result = $service->getRiskIntelligenceNews('us', true);

        $this->assertCount(1, $result);
        $this->assertEquals('Supply Chain Crisis Hits Global Trade', $result[0]->title);
        $this->assertEquals('https://example.com/news/supply-chain-crisis', $result[0]->url);
        $this->assertEquals('Global Trade News', $result[0]->sourceName);
    }

    /** @test */
    public function test_it_dispatches_sync_news_job_correctly(): void
    {
        Queue::fake();

        SyncNewsJob::dispatch('us');

        Queue::assertPushed(SyncNewsJob::class, function ($job) {
            return $job->tries === 3;
        });
    }
}
