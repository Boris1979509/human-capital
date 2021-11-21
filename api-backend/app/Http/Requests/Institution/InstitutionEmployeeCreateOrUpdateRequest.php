<?php

namespace App\Http\Requests\Institution;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionEmployeeCreateOrUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO: ограничить права для создания/обновления
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:128',
            'middle_name' => 'sometimes|string|max:128',
            'last_name' => 'required|string|max:128',
            'position' => 'required|string|max:128',
            'avatar' => 'sometimes|integer|exists:media,id'
        ];
    }

    public function getInstitutionEmployeeData(): array
    {
        return $this->only([
            'first_name',
            'middle_name',
            'last_name',
            'position',
            'approved',
        ]);
    }
}
