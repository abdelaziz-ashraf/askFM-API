<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => (int)    $this->id,
            'name'       => (string) $this->name,
            'email'      => (string) $this->email,
            'image'      => (string) $this->image,
            'created_at' => (string) $this->created_at,
        ];
    }
}
