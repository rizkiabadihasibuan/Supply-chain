<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\SentimentDictionary;
use Illuminate\Database\Eloquent\Collection;

interface DictionaryServiceInterface
{
    /**
     * Get list of dictionaries.
     *
     * @return Collection<int, SentimentDictionary>
     */
    public function getDictionaries(): Collection;

    /**
     * Find dictionary by ID.
     *
     * @param int $id
     * @return SentimentDictionary|null
     */
    public function getDictionaryById(int $id): ?SentimentDictionary;

    /**
     * Add vocabulary word to dictionary.
     *
     * @param int $dictionaryId
     * @param string $type
     * @param array<string, mixed> $wordData
     * @return void
     */
    public function addWordToDictionary(int $dictionaryId, string $type, array $wordData): void;
}
