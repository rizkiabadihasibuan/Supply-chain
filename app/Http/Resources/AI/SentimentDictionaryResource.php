<?php

namespace App\Http\Resources\AI;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class SentimentDictionaryResource extends BaseResource
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
            'word' => $this->word,
            'weight' => (float) $this->weight,
            'type' => $this->type,
        ];
    }
}