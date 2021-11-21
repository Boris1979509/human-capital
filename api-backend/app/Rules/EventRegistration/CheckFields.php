<?php

namespace App\Rules\EventRegistration;

use App\Models\Journal\Content;
use Illuminate\Contracts\Validation\Rule;

class CheckFields implements Rule
{
    protected Content $event;

    public function __construct(Content $event)
    {
        $this->event = $event;
    }

    public function passes($attribute, $value): bool
    {
        $requiredFields = collect(['name', 'email', 'phone']);
        $passedFields = collect($value);
        foreach ($requiredFields as $requiredField) {
            if (!$passedFields->keys()->contains($requiredField) || !$passedFields->get($requiredField)) {
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
