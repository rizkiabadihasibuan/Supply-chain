<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Setting;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository extends BaseRepository implements SettingRepositoryInterface
{
    /**
     * SettingRepository constructor.
     *
     * @param Setting $model
     */
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('key', 'like', '%' . $term . '%')
            ->orWhere('group', 'like', '%' . $term . '%')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['group'])) {
            $query->byGroup($filters['group']);
        }

        if (isset($filters['autoload'])) {
            $query->where('autoload', (bool) $filters['autoload']);
        }

        return $query->get();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = $this->model->where('key', strtolower(trim($key)))->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, string $group = 'system'): void
    {
        $this->model->updateOrCreate(
            ['key' => strtolower(trim($key))],
            [
                'value' => (string) $value,
                'group' => strtolower(trim($group)),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getAutoloadSettings(): Collection
    {
        return $this->model->autoload()->get();
    }
}
