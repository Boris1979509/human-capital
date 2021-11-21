<?php

namespace App\Http\Requests\Journal;

use App\Rules\EventRegistration\CheckFields;
use App\Rules\EventRegistration\CheckQuestions;
use Illuminate\Foundation\Http\FormRequest;

class EventRegistrationChangeStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ids' => [
                'required',
                'array',
            ],
            'ids.*' => [
                'integer',
                'exists:event_registrations,id'
            ]
        ];
    }

    public function getRegistrationsIds(): array
    {
        return $this->get('ids');
    }
}
