<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserEmployerSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        //TODO: ограничить права для создания/обновления
        return true;
    }

    public function rules(): array
    {
        return [
            'notify_responses' => 'nullable|boolean',
            'notify_from_applicants' => 'nullable|boolean',
            'notify_event_registration' => 'nullable|boolean',
            'notify_messages' => 'nullable|boolean',
        ];
    }

    public function getUserEmployerSettingData(): array
    {
        return $this->only([
            'notify_responses',
            'notify_from_applicants',
            'notify_event_registration',
            'notify_messages',
        ]);
    }
}
