<?php

namespace App\Rules\EventRegistration;

use App\Models\Journal\Content;
use Illuminate\Contracts\Validation\Rule;

class CheckQuestions implements Rule
{
    protected Content $event;

    public function __construct(Content $event)
    {
        $this->event = $event;
    }

    public function passes($attribute, $value): bool
    {
        $requiredQuestions = collect($this->event->registration_questions);
        $passedQuestions = collect($value);
        foreach ($requiredQuestions as $requiredQuestion) {
            if (!$passedQuestions->keys()->contains($requiredQuestion) || !$passedQuestions->get($requiredQuestion)) {
                return false;
            }
        }
        return true;
    }

    public function message()
    {
        return 'Заполнены не все поля для регистрации';
    }
}
