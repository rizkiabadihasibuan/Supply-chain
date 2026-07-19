<?php

namespace App\Http\Resources\Weather;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class WeatherResource extends BaseResource
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
            'port_code' => $this->port_code,
            'temperature' => $this->temperature,
            'condition' => $this->condition,
            'wind_speed' => $this->wind_speed,
            'country' => new \App\Http\Resources\Country\CountryResource($this->whenLoaded('country')),
        ];
    }
}