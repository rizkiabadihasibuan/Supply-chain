<?php

namespace App\Http\Resources\Authentication;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class UserResource extends BaseResource
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
            'email' => $this->email,
            'is_active' => (bool) $this->is_active,
            // Conditional attribute
            'email_verified_at' => $this->when($this->email_verified_at, $this->email_verified_at),
            // Relationships
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            // Merge when condition
            $this->mergeWhen($request->user() && $request->user()->is_admin, [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]),
        ];
    }
}