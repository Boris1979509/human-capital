<?php

namespace App\Http\Requests\Institution;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionCreateOrUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO: ограничить права для создания/обновления
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string',
            'short_name' => 'required|string',
            'description' => 'required|string',
            'city_id' => 'nullable|integer',
            'inst_type_id' => 'sometimes|nullable|integer',
            'inst_diploma_id' => 'sometimes|nullable|integer',
            'count_students' => 'sometimes|nullable|integer',
            'count_programs' => 'sometimes|nullable|integer',
            'avg_score' => 'sometimes|nullable|integer',
            'avg_salary' => 'sometimes|nullable|integer',
            'rate_employment' => 'sometimes|nullable|integer',
            'entrance_test' => 'sometimes|nullable|boolean',
            'entrance_test_description' => 'sometimes|nullable|string',
            'employment_assistance' => 'sometimes|nullable|digits:1',
            'rating_show' => 'sometimes|nullable|boolean',
            'show_rating_students' => 'sometimes|nullable|boolean',
            'show_rating_employers' => 'sometimes|nullable|boolean',
            'avg_score_ege' => 'sometimes|nullable|integer',
            'avg_score_oge' => 'sometimes|nullable|integer',
            'percent_enrolled_budget' => 'sometimes|nullable|integer',
            'count_directions' => 'sometimes|nullable|integer',

            'website' => 'nullable|string',

            'contacts' => 'sometimes|nullable|array',
            'contacts.*.name' => 'required|string',
            'contacts.*.address' => 'required|string',
            'contacts.*.coords' => 'sometimes|nullable|array',
            'contacts.*.emails' => 'required|array',
            'contacts.*.phones' => 'required|array',

            'kpp' => 'nullable|digits_between:1,9',
            'ogrn' => 'nullable|digits_between:1,13',
            'bank_inn' => 'nullable|digits_between:1,10',
            'account_corr' => 'nullable|digits_between:1,20',
            'account' => 'nullable|digits_between:1,20',
            'bik' => 'nullable|digits_between:1,9',
        ];
    }

    public function getInstitutionData(): array
    {
        return $this->only([
            'full_name',
            'short_name',
            'city_id',
            'inst_type_id',
            'inst_diploma_id',
            'description',
            'contact_description',
            'count_students',
            'count_programs',
            'avg_score',
            'avg_salary',
            'rating_students',
            'rating_employers',
            'rate_employment',
            'entrance_test',
            'entrance_test_description',
            'employment_assistance',
            'rating_show',
            'show_rating_students',
            'show_rating_employers',
            'avg_score_ege',
            'avg_score_oge',
            'percent_enrolled_budget',
            'count_directions',

            'website',
            'link_vk',
            'link_fb',

            'inn',
            'ogrn',
            'bank',
            'bank_inn',
            'account',
            'account_corr',
            'bik',
            'kpp',
            'oktmo',

            'contacts',
        ]);
    }
}
