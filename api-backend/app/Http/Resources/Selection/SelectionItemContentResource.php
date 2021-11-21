<?php

namespace App\Http\Resources\Selection;

use App\Models\Selection\SelectionItem;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SelectionItem */
class SelectionItemContentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'published_at' => $this->published_at,
        ];
    }
}
