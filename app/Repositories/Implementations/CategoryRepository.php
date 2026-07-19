<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\ArticleCategory;
use App\Models\NewsCategory;
use App\Models\PortCategory;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * CategoryRepository constructor.
     *
     * @param ArticleCategory $model
     */
    public function __construct(ArticleCategory $model)
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
        if (!empty($filters['type'])) {
            return $this->getByType($filters['type']);
        }

        return $this->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getByType(string $type): Collection
    {
        return match (strtolower($type)) {
            'news' => NewsCategory::all(),
            'port' => PortCategory::all(),
            default => ArticleCategory::all(),
        };
    }
}
