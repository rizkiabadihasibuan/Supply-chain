<?php

namespace App\Integrations\Support;

class ApiAuthentication
{
    protected $token;

    public function __construct(?string $token = null)
    {
        $this->token = $token;
    }

    public function getHeaders(): array
    {
        if ($this->token) {
            return ['Authorization' => 'Bearer ' . $this->token];
        }
        return [];
    }
}