<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Services\PortService;
use App\Jobs\SyncPortJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PortApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_fetch_ports_from_api_and_return_dtos(): void
    {
        Http::fake([
            'https://msi.nga.mil/api/*' => Http::response([
                'features' => [
                    [
                        'attributes' => [
                            'index_no' => '12345',
                            'port_name' => 'Singapore',
                            'country' => 'Singapore',
                            'harborsize' => 'L',
                            'harbortype' => 'CN',
                            'supplies_prov' => 'Y',
                            'repairs' => 'Y',
                            'drydock' => 'Y',
                            'fuel' => 'Y',
                            'water' => 'Y'
                        ],
                        'geometry' => [
                            'y' => 1.290270,
                            'x' => 103.851959
                        ]
                    ]
                ]
            ], 200)
        ]);

        $service = app(PortService::class);
        $result = $service->searchPorts('', 'Singapore', true);

        $this->assertCount(1, $result);
        $this->assertEquals('Singapore', $result[0]->name);
        $this->assertEquals(1.290270, $result[0]->latitude);
        $this->assertEquals('L', $result[0]->status);
    }

    /** @test */
    public function test_it_dispatches_sync_port_job_correctly(): void
    {
        Queue::fake();

        SyncPortJob::dispatch('Singapore');

        Queue::assertPushed(SyncPortJob::class, function ($job) {
            return $job->tries === 3;
        });
    }
}
