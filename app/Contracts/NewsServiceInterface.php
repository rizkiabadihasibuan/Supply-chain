<?php
namespace App\Contracts;
/** Interface untuk News Service */
interface NewsServiceInterface {
    public function getLatest(int $limit = 10): array;
    public function getByTopic(string $topic): array;
}
