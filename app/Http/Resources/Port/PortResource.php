<?php

namespace App\Http\Resources\Port;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class PortResource extends BaseResource
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
            'country' => new \App\Http\Resources\Country\CountryResource($this->whenLoaded('country')),
            'latitude' => $this->whenNotNull($this->latitude),
            'longitude' => $this->whenNotNull($this->longitude),
        ];
    }
}