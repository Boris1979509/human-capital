<?php

namespace App\Http\Resources\Institution;

use App\Http\Resources\CityResource;
use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\City;
use App\Models\Dictionary;
use App\Models\Institution\Institution;
use App\Models\Rating;
use App\Models\RatingCalculator;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Institution */
class InstitutionResource extends JsonResource
{
    public function toArray($request): array
    {
        $settings = array();

        if ($this->settings) {
            foreach ($this->settings as $s) {
                $settings[$s->key] = $s->type == 'boolean' ? (boolean) $s->value : (int) $s->value;
            }
        }

        return array_merge(parent::toArray($request), [
            'progress' => $this->getProgress(),
            'settings' => $settings,
            'employees' => InstitutionEmployeeResource::collection($this->employees),
            'managers' => $this->managers ?? null,
            'avatar' => new MediaResource($this->getFirstMedia('avatar')),
            'city' => new CityResource(City::find($this->city_id)),
            'type' => new DictionaryValueResource(Dictionary::getById($this->inst_type_id)),
            'diploma' => new DictionaryValueResource(Dictionary::getById($this->inst_diploma_id)),
            'rating' => (new RatingCalculator($this->ratings))->calculate(),
        ]);
    }
}
