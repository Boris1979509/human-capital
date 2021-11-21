<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserEmployerRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO: ограничить права для создания/обновления
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'sometimes|string',
            'branch_id' => 'sometimes|integer',
            'count_employees' => 'sometimes|integer',
            'is_internship' => 'sometimes|boolean',
            'city_id' => 'sometimes|integer',
            'address' => 'sometimes|string',
            'coords' => 'sometimes|nullable|array',
            'website' => 'sometimes|string',
            'show_contacts' => 'sometimes|boolean',
            'show_vacancies_count' => 'sometimes|boolean',
            'contacts' => 'sometimes|array',
        ];
    }

    public function getEmployerData(): array
    {
        return $this->only([
            'name',
            'description',
            'branch_id',
            'count_employees',
            'is_internship',
            'city_id',
            'address',
            'coords',
            'website',
            'show_contacts',
            'show_vacancies_count',
            'contacts',
        ]);
    }
}
