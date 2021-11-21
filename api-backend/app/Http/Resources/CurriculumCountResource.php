<?php

namespace App\Http\Resources;

use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin InstitutionCurriculum
 */
class CurriculumCountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->curriculumType->id,
            'name' => $this->curriculumType->option,
            'count' => $this->count
        ];
    }
}
