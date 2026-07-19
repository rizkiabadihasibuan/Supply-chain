<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Language;
use Illuminate\Database\Eloquent\Collection;

interface LanguageServiceInterface
{
    /**
     * Get all languages.
     *
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection;

    /**
     * Find language by code.
     *
     * @param string $code
     * @return Language|null
     */
    public function getLanguageByCode(string $code): ?Language;
}
