<?php

declare(strict_types=1);

namespace App\DTO\Mappers;

use App\DTO\NewsArticleDTO;
use App\Models\NewsArticle;

class NewsMapper
{
    /**
     * Convert NewsArticle model to NewsArticleDTO.
     *
     * @param NewsArticle $model
     * @return NewsArticleDTO
     */
    public static function toDTO(NewsArticle $model): NewsArticleDTO
    {
        return NewsArticleDTO::fromArray([
            'id' => $model->id,
            'country_id' => $model->country_id,
            'source_id' => $model->source_id,
            'category_id' => $model->category_id,
            'title' => $model->title,
            'description' => $model->description,
            'content' => $model->content,
            'url' => $model->url,
            'image_url' => $model->image_url,
            'author' => $model->author,
            'published_at' => $model->published_at?->toDateTimeString(),
            'language' => $model->language,
            'sentiment_status' => $model->sentiment_status,
            'risk_score_reference' => $model->risk_score_reference,
        ]);
    }
}
