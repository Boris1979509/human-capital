<?php

namespace App\Http\Resources\Selection;

use App\Http\Resources\DictionaryValueResource;
use App\Models\Dictionary;
use App\Models\Selection\SelectionItem;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SelectionItem */
class SelectionItemEventResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'published_at' => $this->published_at,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'address' => $this->address,
            'phone' => $this->phone,
            'target_audience' => DictionaryValueResource::collection($this->targetAudience),
            'participants_age' => DictionaryValueResource::collection($this->participantsAge),
            'tags' => $this->tags,
        ];
    }
}
