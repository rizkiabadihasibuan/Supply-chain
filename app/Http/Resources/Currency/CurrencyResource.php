<?php

namespace App\Http\Resources\Currency;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class CurrencyResource extends BaseResource
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
            'code' => $this->code,
            'name' => $this->name,
            'symbol' => $this->symbol,
            'exchange_rates' => ExchangeRateResource::collection($this->whenLoaded('exchangeRates')),
        ];
    }
}