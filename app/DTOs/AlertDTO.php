<?php

declare(strict_types=1);

namespace App\DTOs;

class AlertDTO
{
    public readonly ?int $id;
    public readonly string $countryName;
    public readonly string $alertType;
    public readonly string $severity;
    public readonly string $title;
    public readonly ?string $description;
    public readonly string $triggeredBy;
    public readonly float $currentScore;
    public readonly float $previousScore;
    public readonly string $trend;
    public readonly string $createdAt;
    public readonly string $status;

    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? (int) $data['id'] : null;
        $this->countryName = $data['countryName'] ?? '';
        $this->alertType = $data['alertType'] ?? '';
        $this->severity = $data['severity'] ?? 'Medium';
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->triggeredBy = $data['triggeredBy'] ?? '';
        $this->currentScore = (float) ($data['currentScore'] ?? 0.0);
        $this->previousScore = (float) ($data['previousScore'] ?? 0.0);
        $this->trend = $data['trend'] ?? 'Stable';
        $this->createdAt = $data['createdAt'] ?? now()->toIso8601String();
        $this->status = $data['status'] ?? 'New';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'countryName' => $this->countryName,
            'alertType' => $this->alertType,
            'severity' => $this->severity,
            'title' => $this->title,
            'description' => $this->description,
            'triggeredBy' => $this->triggeredBy,
            'currentScore' => $this->currentScore,
            'previousScore' => $this->previousScore,
            'trend' => $this->trend,
            'createdAt' => $this->createdAt,
            'status' => $this->status,
        ];
    }
}
