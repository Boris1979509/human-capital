<?php

namespace App\Http\Resources;

use App\Models\Dictionary;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Dictionary */
class DictionaryValueWithTyoeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->option,
            'type' => [
                'id' => $this->type,
                'name' => Dictionary::TYPES[$this->type]
            ]
        ];
    }
}
