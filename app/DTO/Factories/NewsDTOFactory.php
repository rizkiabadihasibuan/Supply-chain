<?php

declare(strict_types=1);

namespace App\DTO\Factories;

use App\DTO\NewsDTO;

class NewsDTOFactory
{
    /**
     * Create NewsDTO from external GNews API payload.
     *
     * @param array<string, mixed> $payload
     * @return NewsDTO
     */
    public static function createFromApi(array $payload): NewsDTO
    {
        return NewsDTO::fromArray([
            'articles' => $payload['articles'] ?? [],
            'totalArticles' => $payload['totalArticles'] ?? 0,
        ]);
    }
}
