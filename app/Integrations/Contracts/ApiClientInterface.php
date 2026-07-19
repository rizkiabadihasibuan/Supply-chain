<?php

namespace App\Integrations\Contracts;

interface ApiClientInterface
{
    public function get(string $endpoint, array $query = []): array;
    public function post(string $endpoint, array $data = []): array;
    public function put(string $endpoint, array $data = []): array;
    public function delete(string $endpoint, array $data = []): array;
}