<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Services\Contracts\NotificationServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class NotificationService implements NotificationServiceInterface
{
    /**
     * @var NotificationRepositoryInterface
     */
    protected NotificationRepositoryInterface $notifRepo;

    /**
     * NotificationService constructor.
     *
     * @param NotificationRepositoryInterface $notifRepo
     */
    public function __construct(NotificationRepositoryInterface $notifRepo)
    {
        $this->notifRepo = $notifRepo;
    }

    /**
     * @inheritDoc
     */
    public function sendNotification(
        int $userId,
        string $title,
        string $message,
        string $type = 'info',
        string $priority = 'medium'
    ): Notification {
        return $this->notifRepo->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'priority' => $priority,
            'is_read' => false,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getUserUnreadNotifications(int $userId): Collection
    {
        return $this->notifRepo->getUnreadNotifications($userId);
    }

    /**
     * @inheritDoc
     */
    public function markAsRead(int $notificationId): void
    {
        $this->notifRepo->markAsRead($notificationId);
    }

    /**
     * @inheritDoc
     */
    public function markAllAsRead(int $userId): void
    {
        $this->notifRepo->markAllAsRead($userId);
    }

    /**
     * @inheritDoc
     */
    public function savePreferences(int $userId, array $preferences): void
    {
        $this->notifRepo->savePreference($userId, $preferences);
    }
}
