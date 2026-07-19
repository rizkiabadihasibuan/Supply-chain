<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_system_health_endpoint_returns_valid_orchestration_data(): void
    {
        // Preset mock stats in cache
        Cache::put('api_status_weather', 'Operational');
        Cache::put('api_last_sync_weather', '2026-07-19T10:00:00Z');
        
        Cache::put('api_status_countries', 'Operational');
        Cache::put('api_last_sync_countries', '2026-07-19T09:00:00Z');

        $response = $this->getJson('/api/v1/system/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'status',
                    'database',
                    'cache',
                    'queue' => [
                        'connection',
                        'pending_jobs',
                        'failed_jobs'
                    ],
                    'apis' => [
                        'weather' => ['status', 'last_sync'],
                        'exchange_rate' => ['status', 'last_sync'],
                        'world_bank' => ['status', 'last_sync'],
                        'countries' => ['status', 'last_sync'],
                        'news' => ['status', 'last_sync'],
                        'port' => ['status', 'last_sync']
                    ],
                    'timestamp'
                ],
                'timestamp'
            ])
            ->assertJsonFragment([
                'status' => 'Operational',
                'last_sync' => '2026-07-19T10:00:00Z'
            ]);
    }
}
