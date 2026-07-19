<?php

namespace App\Http\Resources\News;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class NewsArticleResource extends BaseResource
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
            'title' => $this->title,
            'url' => $this->url,
            'source' => $this->whenLoaded('source', function() {
                return new NewsSourceResource($this->source);
            }),
            'category' => $this->whenLoaded('category', function() {
                return new NewsCategoryResource($this->category);
            }),
            'sentiment_score' => $this->whenNotNull($this->sentiment_score),
            'published_at' => $this->published_at,
        ];
    }
}