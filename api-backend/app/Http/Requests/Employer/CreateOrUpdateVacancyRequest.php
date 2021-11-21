<?php

namespace App\Http\Requests\Employer;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateVacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->type === User::TYPE_USER_EMPLOYER;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'profession_id' => 'required|exists:professions,id',
            'salary_min' => 'sometimes|nullable|integer',
            'salary_max' => 'sometimes|nullable|integer',
            'salary_negotiable' => 'required|boolean',
            'experience_level' => 'nullable|integer|exists:dictionaries,id',
            'employment_type' => 'nullable|integer|exists:dictionaries,id',
            'schedule' => 'nullable|integer|exists:dictionaries,id',
            'is_internship' => 'required|boolean',
            'competitions' => 'required|array',
            'skills' => 'nullable|array',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'conditions' => 'nullable|string',
            'description' => 'nullable|string',
            'city_id' => 'nullable|exists:cities,id',
            'working_address' => 'nullable|string',
            'coords' => 'nullable|array',
            'is_working_address_visible' => 'required|boolean',
            'show_chat' => 'required|boolean',
        ];
    }

    public function getVacancyAttributes(): array
    {
        return $this->only(array_keys($this->rules()));
    }
}
