<?php

namespace App\Integrations\GNews;

class NewsMapper
{
    /**
     * Map GNews JSON Response to array of Internal NewsDTO
     */
    public function mapCollection(array $response): array
    {
        $articles = $response['articles'] ?? [];
        
        $dtos = [];
        foreach ($articles as $article) {
            $dtos[] = new NewsDTO([
                'title' => $article['title'] ?? '',
                'description' => $article['description'] ?? '',
                'content' => $article['content'] ?? '',
                'url' => $article['url'] ?? '',
                'image' => $article['image'] ?? '',
                'publishedAt' => $article['publishedAt'] ?? '',
                'sourceName' => $article['source']['name'] ?? '',
                'sourceUrl' => $article['source']['url'] ?? '',
            ]);
        }
        
        return $dtos;
    }
}