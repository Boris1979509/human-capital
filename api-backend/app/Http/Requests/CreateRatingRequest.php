<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRatingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'rating' => 'required|numeric',
        ];
    }
}
