<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO: ограничить права для создания/обновления
        return true;
    }

    public function rules(): array
    {
        return [
            'skills' => 'required|array',
            'qualities' => 'required|array',
            'jobs' => 'sometimes|nullable|array',
            'jobs.*.company' => 'required|string',
            'jobs.*.website' => 'nullable|string',
            'jobs.*.position' => 'required|string',
            'jobs.*.description' => 'required|string',
            'jobs.*.year_begin' => 'nullable|integer',
            'jobs.*.year_end' => 'nullable|integer',
            'jobs.*.month_begin' => 'nullable|integer',
            'jobs.*.month_end' => 'nullable|integer',
            'jobs.*.until_now' => 'required|boolean',
        ];
    }
}
