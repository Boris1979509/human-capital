<?php

namespace App\Http\Requests\Employer;

use App\Models\Employer\VacancyResponse;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacancyResponseCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->type === User::TYPE_USER_PERSONAL;
    }

    public function rules(): array
    {
        return [
            'covering_letter' => 'nullable|string',
            'cv_type' => Rule::in([VacancyResponse::CV_TYPE_GENERATED, VacancyResponse::CV_TYPE_UPLOADED]),
            'cv_file_id' => 'nullable|exists:media,id',
        ];
    }

    public function getVacancyResponseData(): array
    {
        return array_merge(
            $this->only(array_keys($this->rules())),
            ['user_id' => auth()->id()]
        );
    }
}
