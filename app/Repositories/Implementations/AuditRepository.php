<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\AuditLog;
use App\Repositories\Interfaces\AuditRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AuditRepository extends BaseRepository implements AuditRepositoryInterface
{
    /**
     * AuditRepository constructor.
     *
     * @param AuditLog $model
     */
    public function __construct(AuditLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('action', 'like', '%' . $term . '%')
            ->orWhere('module', 'like', '%' . $term . '%')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['user_id'])) {
            $query->byUser((int) $filters['user_id']);
        }

        if (!empty($filters['module'])) {
            $query->byModule($filters['module']);
        }

        if (!empty($filters['period']) && $filters['period'] === 'today') {
            $query->today();
        }

        return $query->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function log(
        int $userId,
        string $action,
        string $module,
        ?array $old,
        ?array $new,
        ?string $ip,
        ?string $ua
    ): AuditLog {
        return $this->create([
            'user_id' => $userId,
            'action' => $action,
            'module' => $module,
            'old_value' => $old,
            'new_value' => $new,
            'ip_address' => $ip,
            'user_agent' => $ua,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getRecentLogs(int $limit = 50): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }
}
