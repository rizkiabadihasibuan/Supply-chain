<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Port;
use Illuminate\Database\Eloquent\Collection;

interface PortServiceInterface
{
    /**
     * Get list of all ports.
     *
     * @return Collection<int, Port>
     */
    public function getPorts(): Collection;

    /**
     * Get specific port details.
     *
     * @param int $id
     * @return Port|null
     */
    public function getPortById(int $id): ?Port;

    /**
     * Get specific port details by its unique WPI code.
     *
     * @param string $code
     * @return Port|null
     */
    public function getPortByCode(string $code): ?Port;

    /**
     * Search ports by matching query.
     *
     * @param string $term
     * @return Collection<int, Port>
     */
    public function searchPorts(string $term): Collection;

    /**
     * Filter ports.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Port>
     */
    public function filterPorts(array $filters): Collection;
}
