<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Language;
use App\Repositories\Interfaces\LanguageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    /**
     * LanguageRepository constructor.
     *
     * @param Language $model
     */
    public function __construct(Language $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByCode(string $code): ?Language
    {
        return $this->model->where('code', strtolower(trim($code)))->first();
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('name', 'like', '%' . $term . '%')
            ->orWhere('code', 'like', '%' . $term . '%')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        return $this->findAll();
    }
}
