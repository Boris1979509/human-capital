<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'parent_id' => 'sometimes|integer|exists:comments,id'
        ];
    }
}
