<?php

namespace App\Http\Requests\Institution;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO: ограничить права для создания/обновления
        return true;
    }

    public function rules(): array
    {
        return [
            'is_email_notifications' => 'nullable|boolean',
            'notify_frequency_id' => 'nullable|integer',
        ];
    }

    public function getInstitutionSettingData(): array
    {
        return $this->only([
            'is_email_notifications',
            'notify_frequency_id',
        ]);
    }
}
