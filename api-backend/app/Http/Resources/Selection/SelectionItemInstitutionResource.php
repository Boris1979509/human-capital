<?php

namespace App\Http\Resources\Selection;

use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\Dictionary;
use App\Models\Selection\Selection;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Selection */
class SelectionItemInstitutionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'full_name' => $this->full_name,
            'type' => new DictionaryValueResource(Dictionary::getById($this->inst_type_id)),
            'diploma' => new DictionaryValueResource(Dictionary::getById($this->inst_diploma_id)),
            'count_students' => $this->count_students,
            'count_curricula' => $this->curricula->count(),
            'avg_score' => $this->avg_score,
            'avg_salary' => $this->avg_salary,
            'rating_students' => $this->rating_students,
            'rate_employment' => $this->rate_employment,
        ];
    }
}
