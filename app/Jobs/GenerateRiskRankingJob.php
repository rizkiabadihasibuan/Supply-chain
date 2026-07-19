<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\RiskRankingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateRiskRankingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60];

    protected array $filters;
    protected string $sortBy;
    protected string $direction;
    protected ?int $limit;

    public function __construct(
        array $filters = [],
        string $sortBy = 'overall_score',
        string $direction = 'desc',
        ?int $limit = null
    ) {
        $this->filters = $filters;
        $this->sortBy = $sortBy;
        $this->direction = $direction;
        $this->limit = $limit;
    }

    public function handle(RiskRankingService $service): void
    {
        Log::info("GenerateRiskRankingJob: Generating and caching ranking for type: {$this->sortBy}...");
        $service->getRanking($this->filters, $this->sortBy, $this->direction, $this->limit, true);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateRiskRankingJob failed permanently: ' . $exception->getMessage());
    }
}
