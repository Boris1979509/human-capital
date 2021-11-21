<?php

namespace App\Http\Resources\Curricula;

use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\Dictionary;
use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin InstitutionCurriculum
 */
class CurriculumDetailedForPublicResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => new DictionaryValueResource($this->curriculumType),
            'description' => $this->description,
            'budget_places' => $this->budget_places,
            'passing_score' => $this->passing_score,
            'duration' => $this->duration,
            'competitions' => $this->competitions,
            'is_admission_exam' => $this->is_admission_exam,
            'admissions_exams' => collect($this->admission_exams)->map(function ($admissionExam) {
                if (isset($admissionExam['subject'])) {
                    $admissionExam['subject'] = new DictionaryValueResource(Dictionary::getById($admissionExam['subject']));
                }
                return $admissionExam;
            }),
            'is_admission_olympiad' => $this->is_admission_olympiad,
            'admission_olympiad_conditions' => $this->admission_olympiad_conditions,
            'is_admission_additional_exam' => $this->is_admission_additional_exam,
            'admission_additional_exam_conditions' => $this->admission_additional_exam_conditions,
            'learning_options' => (new CurriculaLearningOptionsResource($this->learning_options))->toArray($request),
            'institution' => [
                'id' => $this->institution->id,
                'full_name' => $this->institution->full_name,
                'avatar' => new MediaResource($this->institution->getFirstMedia('avatar'))
            ],
            'direction_of_study' => $this->direction_of_study,
            'is_published' => $this->is_published,
            'is_favorited' => $this->isFavorited(auth()->id()),
            'is_calendar_entry' => $this->isCalendarEntry(),
            'cost'=>$this->getMinPrice()
        ];
    }
}
