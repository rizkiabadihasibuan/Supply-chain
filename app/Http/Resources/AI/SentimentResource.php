<?php

namespace App\Http\Resources\AI;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class SentimentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'score' => $this->score,
            'label' => $this->label,
            'confidence' => $this->whenNotNull($this->confidence),
        ];
    }
}