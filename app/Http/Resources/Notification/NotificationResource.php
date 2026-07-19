<?php

namespace App\Http\Resources\Notification;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class NotificationResource extends BaseResource
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
            'message' => $this->message,
            'type' => $this->type,
            'read_at' => $this->read_at,
            'data' => $this->whenNotNull($this->data),
            'created_at' => $this->created_at,
        ];
    }
}