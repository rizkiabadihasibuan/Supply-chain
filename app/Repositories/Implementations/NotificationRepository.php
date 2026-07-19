<?php

declare(strict_types=1);

namespace App\Repositories\Implementations;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    /**
     * NotificationRepository constructor.
     *
     * @param Notification $model
     */
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function search(string $term): Collection
    {
        return $this->model->where('title', 'like', '%' . $term . '%')
            ->orWhere('message', 'like', '%' . $term . '%')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        if (isset($filters['is_read'])) {
            $query->where('is_read', (bool) $filters['is_read']);
        }

        if (!empty($filters['priority'])) {
            $query->byPriority($filters['priority']);
        }

        return $query->latest()->get();
    }

    /**
     * @inheritDoc
     */
    public function getUnreadNotifications(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->unread()
            ->latest()
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function markAsRead(int $notificationId): void
    {
        $notification = $this->findById($notificationId);

        if ($notification) {
            $notification->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function markAllAsRead(int $userId): void
    {
        $this->model->where('user_id', $userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);
    }

    /**
     * @inheritDoc
     */
    public function savePreference(int $userId, array $preferences): void
    {
        NotificationPreference::updateOrCreate(
            ['user_id' => $userId],
            $preferences
        );
    }
}
