<?php

namespace App\Http\Resources\Currency;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class ExchangeRateResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'base_currency' => $this->base_currency,
            'target_currency' => $this->target_currency,
            'rate' => (float) $this->rate,
            'date' => $this->date,
        ];
    }
}