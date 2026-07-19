<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Language;
use Illuminate\Database\Eloquent\Collection;

interface LanguageRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find language by its code.
     *
     * @param string $code
     * @return Language|null
     */
    public function findByCode(string $code): ?Language;

    /**
     * Search languages.
     *
     * @param string $term
     * @return Collection<int, Language>
     */
    public function search(string $term): Collection;

    /**
     * Filter languages.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Language>
     */
    public function filter(array $filters): Collection;
}
