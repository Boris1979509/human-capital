<?php

namespace App\Http\Resources\Selection;

use App\Http\Resources\DictionaryValueResource;
use App\Models\Dictionary;
use App\Models\Selection\SelectionItem;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SelectionItem */
class SelectionItemCurriculumResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'direction_of_study' => $this->direction_of_study,
            'type' => new DictionaryValueResource(Dictionary::getById($this->type_id)),
            'budget_places' => $this->budget_places,
            'competitions' => $this->competitions,
        ];
    }
}
