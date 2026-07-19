<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Collection;

interface SourceRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search sources by name.
     *
     * @param string $term
     * @return Collection<int, NewsSource>
     */
    public function search(string $term): Collection;

    /**
     * Filter news sources.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, NewsSource>
     */
    public function filter(array $filters): Collection;

    /**
     * Get all active news sources.
     *
     * @return Collection<int, NewsSource>
     */
    public function getActiveSources(): Collection;
}
