<?php

namespace App\Http\Requests\Journal;

use App\Models\EventRegistration;
use App\Rules\EventRegistration\CheckFields;
use App\Rules\EventRegistration\CheckQuestions;
use Illuminate\Foundation\Http\FormRequest;

class EventRegistrationCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fields' => [
                'required',
                'array',
                new CheckFields($this->route('event'))
            ],
            'questions' => [
                'nullable',
                'array',
                new CheckQuestions($this->route('event'))
            ]
        ];
    }

    public function getRegistrationData(): array
    {
        return array_merge(
            [
                'user_id' => auth()->id(),
                'status' => EventRegistration::STATUS_PENDING,
            ],
            $this->only(array_keys($this->rules()))
        );
    }
}
