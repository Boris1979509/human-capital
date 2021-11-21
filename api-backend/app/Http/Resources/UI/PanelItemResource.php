<?php

namespace App\Http\Resources\UI;

use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\DictionaryValueWithTyoeResource;
use App\Models\Dictionary;
use App\Models\UI\PanelItem;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PanelItem */
class PanelItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'sort' => $this->sort,
            'count' => $this->count(),
            'dictionary_id' => new DictionaryValueWithTyoeResource(Dictionary::getById($this->dictionary_id)),
        ];
    }
}
