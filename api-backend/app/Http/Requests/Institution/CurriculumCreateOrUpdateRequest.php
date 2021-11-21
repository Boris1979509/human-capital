<?php

namespace App\Http\Requests\Institution;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumCreateOrUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'direction_of_study' => 'required',
            'description' => 'required',
            'is_published' => 'sometimes|boolean',
            'passing_score' => 'nullable|numeric',
            'competitions' => 'required|array',
            'is_admission_exam' => 'sometimes|boolean',
            'is_admission_olympiad' => 'sometimes|boolean',
            'result_professions' => 'sometimes|array',
            'worth' => 'sometimes|nullable|array',
            'result_skills' => 'sometimes|nullable|array',
            'reviews_enabled' => 'sometimes|boolean',
            'questions_enabled' => 'sometimes|boolean',
            'admission_exams' => 'sometimes|nullable|array',
            'admission_exams.*.subject' => 'nullable|integer|exists:dictionaries,id',
            'learning_options' => 'sometimes|array',
            'learning_options.*.auditory' => 'required|integer|exists:dictionaries,id',
            'learning_options.*.edu_form' => 'sometimes|integer|exists:dictionaries,id',
            'learning_options.*.how_long' => 'required',
            'learning_options.*.cost' => 'nullable|integer',
            'learning_options.*.start_date' => 'required',
        ];
    }

    public function getCurriculumData(): array
    {
        return $this->only([
            'name',
            'is_published',
            'direction_of_study',
            'type_id',
            'description',
            'budget_places',
            'passing_score',
            'duration',
            'competitions',
            'is_admission_exam',
            'admission_exams',
            'is_admission_olympiad',
            'admission_olympiad_conditions',
            'is_admission_additional_exam',
            'admission_additional_exam_conditions',
            'learning_options',
            'result_professions',
            'worth',
            'result_skills',
            'questions_enabled',
        ]);
    }
}
