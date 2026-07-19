<?php

declare(strict_types=1);

namespace App\DTOs;

class DashboardAnalyticsDTO
{
    public readonly string $type;
    public readonly array $data;
    public readonly string $generatedAt;

    public function __construct(string $type, array $data, ?string $generatedAt = null)
    {
        $this->type = $type;
        $this->data = $data;
        $this->generatedAt = $generatedAt ?? now()->toIso8601String();
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
            'generated_at' => $this->generatedAt,
        ];
    }
}
