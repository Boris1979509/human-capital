<?php

namespace App\Http\Resources\Institution;

use App\Http\Resources\Curricula\CurriculaLearningOptionsResource;
use App\Http\Resources\MediaResource;
use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin InstitutionCurriculum
 */
class CurriculumResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type_id' => $this->type_id,
            'learning_options' => (new CurriculaLearningOptionsResource($this->learning_options))->toArray($request),
            'inst_program' => [
                'id' => optional($this->curriculumType)->id,
                'name' => optional($this->curriculumType)->option,
                'slug' => optional($this->curriculumType)->slug,
            ],
            'institution' => [
                'id' => $this->institution->id,
                'full_name' => $this->institution->full_name,
                'avatar' => new MediaResource($this->institution->getFirstMedia('avatar'))
            ],
            'direction_of_study' => $this->direction_of_study,
            'is_published' => $this->is_published
        ];
    }
}
