<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => optional($this->personal)->first_name,
            'last_name' => optional($this->personal)->last_name,
            'avatar' => new MediaResource($this->getFirstMedia('avatar')),
        ];
    }
}
