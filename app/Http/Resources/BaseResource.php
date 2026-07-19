<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class BaseResource extends JsonResource
{
    public $message = 'Success';

    public function additional(array $data)
    {
        if (isset($data['message'])) {
            $this->message = $data['message'];
            unset($data['message']);
        }
        return parent::additional($data);
    }

    public function with(Request $request): array
    {
        return [
            'success' => true,
            'message' => $this->message,
            'meta' => new \stdClass(),
            'links' => new \stdClass(),
            'timestamp' => now()->toIso8601String(),
        ];
    }
}