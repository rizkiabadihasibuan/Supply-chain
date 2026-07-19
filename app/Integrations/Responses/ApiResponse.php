<?php

namespace App\Integrations\Responses;

class ApiResponse
{
    protected $data;
    protected $status;
    protected $meta;

    public function __construct($data, int $status = 200, array $meta = [])
    {
        $this->data = $data;
        $this->status = $status;
        $this->meta = $meta;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}