<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO: ограничить права для создания/обновления
        return true;
    }

    public function rules(): array
    {
        return [
            'education' => 'sometimes|nullable|array',
            'education.*.university' => 'required|string',
            'education.*.specialty' => 'nullable|string',
            'education.*.edu_degree_id' => 'nullable|integer',
            'education.*.edu_status_id' => 'nullable|integer',
            'education.*.edu_quality_id' => 'nullable|integer',
            'education.*.year_begin' => 'nullable|integer',
            'education.*.year_end' => 'nullable|integer',
            'education.*.document_number' => 'nullable|string',
        ];
    }
}
