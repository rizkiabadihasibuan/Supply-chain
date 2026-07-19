<?php

namespace App\Http\Resources\Country;

use App\Http\Resources\BaseCollection;
use Illuminate\Http\Request;

class CountryCollection extends BaseCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}