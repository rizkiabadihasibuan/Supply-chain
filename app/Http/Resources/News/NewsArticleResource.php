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
            'id'               => $this->id,
            'title'            => $this->title,
            'description'      => $this->description,
            'url'              => $this->url,
            'image_url'        => $this->image_url,
            'author'           => $this->author,
            'sentiment_status' => $this->sentiment_status ?? 'neutral',
            'published_at'     => $this->published_at?->toIso8601String(),
        ];
    }
}