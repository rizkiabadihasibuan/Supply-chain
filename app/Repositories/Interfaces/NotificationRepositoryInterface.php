<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

interface NotificationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Search notifications.
     *
     * @param string $term
     * @return Collection<int, Notification>
     */
    public function search(string $term): Collection;

    /**
     * Filter notifications.
     *
     * @param array<string, mixed> $filters
     * @return Collection<int, Notification>
     */
    public function filter(array $filters): Collection;

    /**
     * Get unread notifications for a user.
     *
     * @param int $userId
     * @return Collection<int, Notification>
     */
    public function getUnreadNotifications(int $userId): Collection;

    /**
     * Mark single notification as read.
     *
     * @param int $notificationId
     * @return void
     */
    public function markAsRead(int $notificationId): void;

    /**
     * Mark all notifications of a user as read.
     *
     * @param int $userId
     * @return void
     */
    public function markAllAsRead(int $userId): void;

    /**
     * Update notification preference flags.
     *
     * @param int $userId
     * @param array<string, bool> $preferences
     * @return void
     */
    public function savePreference(int $userId, array $preferences): void;
}
