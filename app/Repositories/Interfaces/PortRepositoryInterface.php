<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Port;
use Illuminate\Database\Eloquent\Collection;

interface PortRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find port by its unique code.
     *
     * @param string $code
     * @return Port|null
     */
    public function findByCode(string $code): ?Port;

    /**
     * Search ports by name or code.
     *
     * @param string $term
     * @return Collection<int, Port>
     */
    public function search(string $term): Collection;

    /**
     * Filter ports by country, category or capacity size.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Port>
     */
    public function filter(array $filters): Collection;
}
