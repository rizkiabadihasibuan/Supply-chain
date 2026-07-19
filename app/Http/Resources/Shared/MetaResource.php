<?php

namespace App\Http\Resources\Shared;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class MetaResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'version' => '1.0.0',
            'author' => 'SupplyChain API',
            'rate_limit' => $this->whenNotNull($this->resource['rate_limit'] ?? null),
        ];
    }
}