<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\NegativeWord;
use App\Models\PositiveWord;
use App\Models\SentimentDictionary;
use App\Repositories\Interfaces\DictionaryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DictionaryRepository extends BaseRepository implements DictionaryRepositoryInterface
{
    /**
     * DictionaryRepository constructor.
     *
     * @param SentimentDictionary $model
     */
    public function __construct(SentimentDictionary $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('name', 'like', '%' . $term . '%')->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function getWords(int $dictionaryId, string $type): Collection
    {
        if (strtolower($type) === 'positive') {
            return PositiveWord::where('dictionary_id', $dictionaryId)->get();
        }

        return NegativeWord::where('dictionary_id', $dictionaryId)->get();
    }

    /**
     * @inheritDoc
     */
    public function addWord(int $dictionaryId, string $type, array $wordData): void
    {
        $data = array_merge($wordData, ['dictionary_id' => $dictionaryId]);

        if (strtolower($type) === 'positive') {
            PositiveWord::create($data);
        } else {
            NegativeWord::create($data);
        }
    }
}
