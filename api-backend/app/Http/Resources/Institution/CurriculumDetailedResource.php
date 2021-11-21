<?php

namespace App\Http\Resources\Institution;

use App\Http\Resources\DictionaryValueResource;
use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin InstitutionCurriculum
 */
class CurriculumDetailedResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_published' => $this->is_published,
            'direction_of_study' => $this->direction_of_study,
            'type_id' => $this->type_id,
            'description' => $this->description,
            'budget_places' => $this->budget_places,
            'passing_score' => $this->passing_score,
            'duration' => $this->duration,
            'competitions' => $this->competitions,
            'is_admission_exam' => $this->is_admission_exam,
            'admission_exams' => $this->admission_exams,
            'is_admission_olympiad' => $this->is_admission_olympiad,
            'admission_olympiad_conditions' => $this->admission_olympiad_conditions,
            'is_admission_additional_exam' => $this->is_admission_additional_exam,
            'admission_additional_exam_conditions' => $this->admission_additional_exam_conditions,
            'learning_options' => $this->learning_options,
            'result_professions' => $this->result_professions,
            'questions_enabled' => $this->questions_enabled,
            'worth' => $this->worth,
            'result_skills' => $this->result_skills,
            'directions' => DictionaryValueResource::collection($this->directions),
        ];
    }
}
