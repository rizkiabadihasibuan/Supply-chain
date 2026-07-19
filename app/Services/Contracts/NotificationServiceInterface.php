<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

interface NotificationServiceInterface
{
    /**
     * Send in-app notification to a user.
     *
     * @param int $userId
     * @param string $title
     * @param string $message
     * @param string $type
     * @param string $priority
     * @return Notification
     */
    public function sendNotification(
        int $userId,
        string $title,
        string $message,
        string $type = 'info',
        string $priority = 'medium'
    ): Notification;

    /**
     * Get unread notifications for a user.
     *
     * @param int $userId
     * @return Collection<int, Notification>
     */
    public function getUserUnreadNotifications(int $userId): Collection;

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
     * Save user preferences for notifications.
     *
     * @param int $userId
     * @param array<string, bool> $preferences
     * @return void
     */
    public function savePreferences(int $userId, array $preferences): void;
}
