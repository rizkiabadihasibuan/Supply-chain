<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Get all records.
     *
     * @param array<int, string> $columns
     * @return Collection<int, Model>
     */
    public function findAll(array $columns = ['*']): Collection;

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @param array<int, string> $columns
     * @return Model|null
     */
    public function findById(int $id, array $columns = ['*']): ?Model;

    /**
     * Create a new record.
     *
     * @param array<string, mixed> $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update an existing record.
     *
     * @param int $id
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Paginate records.
     *
     * @param int $perPage
     * @param array<int, string> $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;
}
