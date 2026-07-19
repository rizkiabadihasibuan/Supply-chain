<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;

class BaseCollection extends ResourceCollection
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
        $pagination = [];
        if ($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $pagination = [
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'per_page' => $this->resource->perPage(),
                'total' => $this->resource->total(),
                'links' => [
                    'first' => $this->resource->url(1),
                    'last' => $this->resource->url($this->resource->lastPage()),
                    'prev' => $this->resource->previousPageUrl(),
                    'next' => $this->resource->nextPageUrl(),
                ]
            ];
        }

        return [
            'success' => true,
            'message' => $this->message,
            'meta' => $pagination ? ['pagination' => $pagination] : new \stdClass(),
            'timestamp' => now()->toIso8601String(),
        ];
    }
}