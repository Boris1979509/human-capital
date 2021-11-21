<?php

namespace App\Filters;

use Illuminate\Support\Str;

class AutocompleteFilter extends FilterRequest
{
    protected function getRules(): array
    {
        return [
            'type' => 'sometimes|integer',
            'q' => 'string'
        ];
    }

    protected function type(string $value): void
    {
        $this->builder->where('type', '=', $value);
    }

    protected function q(string $value): void
    {
        $this->builder->where('word', 'like', Str::lower($value). '%');
    }
}
