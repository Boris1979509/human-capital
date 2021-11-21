<?php

namespace App\Filters;

class PanelFilter extends FilterRequest
{
    protected function getRules(): array
    {
        return [
            'type' => 'sometimes|string'
        ];
    }

    protected function type(string $value): void
    {
        $this->builder->where('type', '=', $value);
    }
}
