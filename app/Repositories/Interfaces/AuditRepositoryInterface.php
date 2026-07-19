<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Collection;

interface AuditRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search audit logs.
     *
     * @param string $term
     * @return Collection<int, AuditLog>
     */
    public function search(string $term): Collection;

    /**
     * Filter audit logs.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, AuditLog>
     */
    public function filter(array $filters): Collection;

    /**
     * Create an audit log entry.
     *
     * @param int $userId
     * @param string $action
     * @param string $module
     * @param array<string, mixed>|null $old
     * @param array<string, mixed>|null $new
     * @param string|null $ip
     * @param string|null $ua
     * @return AuditLog
     */
    public function log(
        int $userId,
        string $action,
        string $module,
        ?array $old,
        ?array $new,
        ?string $ip,
        ?string $ua
    ): AuditLog;

    /**
     * Get recent audit logs list.
     *
     * @param int $limit
     * @return Collection<int, AuditLog>
     */
    public function getRecentLogs(int $limit = 50): Collection;
}
