<?php

namespace App\Http\Resources\Selection;

use App\Http\Resources\MediaResource;
use App\Models\Selection\Selection;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Selection */
class SelectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'annotation' => $this->annotation,
            'description' => $this->description,
            'reading_time' => $this->getReadingTime(),
            'published_at' => $this->published_at,
            'is_published' => $this->is_published,
            'is_advertisement' => $this->is_advertisement,
            'cover' => new MediaResource($this->getFirstMedia('cover')),
            'is_favorited' => $this->isFavorited(auth()->id()),
        ];
    }
}
