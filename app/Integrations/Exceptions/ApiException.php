<?php

namespace App\Integrations\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $statusCode;
    protected $responseBody;

    public function __construct(string $message, int $statusCode = 500, $responseBody = null)
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }
}