<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\SentimentDictionary;
use App\Repositories\Interfaces\DictionaryRepositoryInterface;
use App\Services\Contracts\DictionaryServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class DictionaryService implements DictionaryServiceInterface
{
    /**
     * @var DictionaryRepositoryInterface
     */
    protected DictionaryRepositoryInterface $dictRepo;

    /**
     * DictionaryService constructor.
     *
     * @param DictionaryRepositoryInterface $dictRepo
     */
    public function __construct(DictionaryRepositoryInterface $dictRepo)
    {
        $this->dictRepo = $dictRepo;
    }

    /**
     * @inheritDoc
     */
    public function getDictionaries(): Collection
    {
        return $this->dictRepo->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getDictionaryById(int $id): ?SentimentDictionary
    {
        return $this->dictRepo->findById($id);
    }

    /**
     * @inheritDoc
     */
    public function addWordToDictionary(int $dictionaryId, string $type, array $wordData): void
    {
        $this->dictRepo->addWord($dictionaryId, $type, $wordData);
    }
}
