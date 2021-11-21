<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserPersonalRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO: ограничить права для создания/обновления
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'sex' => 'required|integer',
            'nationality_id' => 'required|integer',
            'city_id' => 'required|integer',
            'country_id' => 'required|integer',
        ];
    }

    public function getPersonalData(): array
    {
        return $this->only([
            'first_name',
            'middle_name',
            'last_name',
            'sex',
            'birthday',
            'nationality_id',
            'city_id',
            'country_id',
            'document_id',
            'document_series',
            'document_number',
            'document_date',
            'inn',
            'snills',
            'link_vk',
            'link_fb',
            'description',
        ]);
    }
}
