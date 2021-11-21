<?php

namespace App\Http\Requests\Employer;

use App\Models\Employer\VacancyResponse;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateApplicantRejectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => 'required|string',
        ];
    }
}
