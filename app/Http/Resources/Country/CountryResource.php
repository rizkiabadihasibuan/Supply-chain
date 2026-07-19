<?php

namespace App\Http\Resources\Country;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;
use App\Http\Resources\Weather\WeatherResource;
use App\Http\Resources\News\NewsArticleResource;

class CountryResource extends BaseResource
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
            'name' => $this->name,
            'iso_code' => $this->code,
            'risk_score' => (float) ($this->riskScore?->overall_score ?? 0.0),
            'is_active' => true,
            'weather' => WeatherResource::collection($this->whenLoaded('weathers')),
            'news' => NewsArticleResource::collection($this->whenLoaded('news')),
        ];
    }
}