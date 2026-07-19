<?php

namespace App\Http\Resources\Watchlist;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class WatchlistResource extends BaseResource
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
            'country' => new \App\Http\Resources\Country\CountryResource($this->whenLoaded('country')),
            'alert_threshold' => (float) $this->alert_threshold,
            'notify_on_news' => (bool) $this->notify_on_news,
            'is_active' => (bool) $this->is_active,
        ];
    }
}