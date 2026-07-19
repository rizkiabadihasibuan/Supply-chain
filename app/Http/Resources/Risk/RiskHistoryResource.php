<?php

namespace App\Http\Resources\Risk;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class RiskHistoryResource extends BaseResource
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
            'previous_score' => (float) $this->previous_score,
            'new_score' => (float) $this->new_score,
            'changed_at' => $this->changed_at,
        ];
    }
}