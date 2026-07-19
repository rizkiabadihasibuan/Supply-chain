<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('name', 'like', '%' . $term . '%')
            ->orWhere('email', 'like', '%' . $term . '%')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        return $query->get();
    }
}
