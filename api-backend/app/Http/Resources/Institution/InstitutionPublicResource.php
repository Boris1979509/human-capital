<?php

namespace App\Http\Resources\Institution;

use App\Http\Resources\CityResource;
use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\City;
use App\Models\Dictionary;
use App\Models\Institution\Institution;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Institution */
class InstitutionPublicResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'short_name' => $this->short_name,
            'city' => new CityResource(City::find($this->city_id)),
            'type' => new DictionaryValueResource(Dictionary::getById($this->inst_type_id)),
            'diploma' => new DictionaryValueResource(Dictionary::getById($this->inst_diploma_id)),
            'description' => $this->description,
            'count_curricula' => $this->curricula->count(),
            'avatar' => new MediaResource($this->getFirstMedia('avatar')),
            'contacts' => $this->contacts,
        ];
    }
}
