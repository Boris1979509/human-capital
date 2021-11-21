<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\Dictionary;
use App\Models\Employer\Employer;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Employer */
class EmployerPublicCardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => new MediaResource($this->getFirstMedia('avatar')),
            'branch' => new DictionaryValueResource(Dictionary::getById($this->branch_id)),
            'vacancies_count' => $this->vacancies_count
        ];
    }
}
