<?php

namespace App\Integrations\GNews;

class NewsDTO
{
    public readonly string $title;
    public readonly string $description;
    public readonly string $content;
    public readonly string $url;
    public readonly string $image;
    public readonly string $publishedAt;
    public readonly string $sourceName;
    public readonly string $sourceUrl;
    
    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->content = $data['content'] ?? '';
        $this->url = $data['url'] ?? '';
        $this->image = $data['image'] ?? '';
        $this->publishedAt = $data['publishedAt'] ?? now()->toIso8601String();
        $this->sourceName = $data['sourceName'] ?? '';
        $this->sourceUrl = $data['sourceUrl'] ?? '';
    }
}