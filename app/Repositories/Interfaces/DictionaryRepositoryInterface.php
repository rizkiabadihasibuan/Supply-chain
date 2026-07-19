<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\SentimentDictionary;
use Illuminate\Database\Eloquent\Collection;

interface DictionaryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search dictionaries by name.
     *
     * @param string $term
     * @return Collection<int, SentimentDictionary>
     */
    public function search(string $term): Collection;

    /**
     * Filter dictionaries.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, SentimentDictionary>
     */
    public function filter(array $filters): Collection;

    /**
     * Get list of words from dictionary by word type.
     *
     * @param int $dictionaryId
     * @param string $type
     * @return Collection
     */
    public function getWords(int $dictionaryId, string $type): Collection;

    /**
     * Add word to dictionary list.
     *
     * @param int $dictionaryId
     * @param string $type
     * @param array<string, mixed> $wordData
     * @return void
     */
    public function addWord(int $dictionaryId, string $type, array $wordData): void;
}
