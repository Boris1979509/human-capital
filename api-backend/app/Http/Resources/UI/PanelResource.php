<?php

namespace App\Http\Resources\UI;

use App\Models\UI\Panel;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Panel */
class PanelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'sort' => $this->sort,
            'color' => $this->color,
            'items' => PanelItemResource::collection($this->items),
        ];
    }
}
