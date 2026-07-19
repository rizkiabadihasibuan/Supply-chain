<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class NotificationDTO implements JsonSerializable
{
    /**
     * NotificationDTO constructor.
     *
     * @param int|null $id
     * @param int $userId
     * @param string $title
     * @param string $message
     * @param string $type
     * @param string $priority
     * @param bool $isRead
     * @param string|null $readAt
     */
    public function __construct(
        public ?int $id,
        public int $userId,
        public string $title,
        public string $message,
        public string $type = 'info',
        public string $priority = 'medium',
        public bool $isRead = false,
        public ?string $readAt = null
    ) {}

    /**
     * Create DTO from array.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            userId: (int) ($data['user_id'] ?? 0),
            title: (string) ($data['title'] ?? ''),
            message: (string) ($data['message'] ?? ''),
            type: (string) ($data['type'] ?? 'info'),
            priority: (string) ($data['priority'] ?? 'medium'),
            isRead: (bool) ($data['is_read'] ?? false),
            readAt: $data['read_at'] ?? null
        );
    }

    /**
     * Convert DTO to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'priority' => $this->priority,
            'is_read' => $this->isRead,
            'read_at' => $this->readAt,
        ];
    }

    /**
     * Convert DTO to JSON.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
