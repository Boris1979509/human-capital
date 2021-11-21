<?php

namespace App\Http\Resources;

use App\Models\Dictionary;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Dictionary */
class DictionaryValueResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->option
        ];
    }
}
