<?php

namespace App\Models\Employer\ResponseState;

use App\Http\Resources\DictionaryValueResource;
use App\Models\Dictionary;

class ApplicantInvite
{
    protected string $message;
    protected int $interviewTypeId;
    protected string $contactPhone;

    public function __construct(string $message, int $interviewTypeId, string $contactPhone)
    {
        $this->message = $message;
        $this->interviewTypeId = $interviewTypeId;
        $this->contactPhone = $contactPhone;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'interview_type' => (new DictionaryValueResource(Dictionary::getById($this->interviewTypeId)))->toArray(null),
            'contact_phone' => $this->contactPhone
        ];
    }
}
