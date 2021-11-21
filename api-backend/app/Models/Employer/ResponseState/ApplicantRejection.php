<?php

namespace App\Models\Employer\ResponseState;

class ApplicantRejection
{
    protected string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
