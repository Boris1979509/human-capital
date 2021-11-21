<?php

namespace App\Http\Resources\Curricula;

use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin InstitutionCurriculum
 */
class CurriculumForPublicResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'learning_options' => (new CurriculaLearningOptionsResource($this->learning_options))->toArray($request),
            'inst_program' => new DictionaryValueResource($this->curriculumType),
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
