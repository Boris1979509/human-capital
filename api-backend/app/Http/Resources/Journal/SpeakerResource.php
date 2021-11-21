<?php

namespace App\Http\Resources\Journal;

use App\Http\Resources\MediaResource;
use App\Models\Journal\EventSpeaker;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin EventSpeaker */
class SpeakerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'avatar' => new MediaResource($this->getFirstMedia('avatar'))
        ];
    }
}
