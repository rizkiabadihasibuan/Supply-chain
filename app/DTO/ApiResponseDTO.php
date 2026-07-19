<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

readonly class ApiResponseDTO implements JsonSerializable
{
    /**
     * ApiResponseDTO constructor.
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param array<string, mixed>|null $errors
     */
    public function __construct(
        public bool $success,
        public string $message,
        public mixed $data = null,
        public ?array $errors = null
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
            success: (bool) ($data['success'] ?? true),
            message: (string) ($data['message'] ?? 'Operation completed successfully.'),
            data: $data['data'] ?? null,
            errors: $data['errors'] ?? null
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
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
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
