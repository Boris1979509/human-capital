<?php

namespace App\Filters;

class SelectionFilter extends FilterRequest
{
    protected function getRules(): array
    {
        return [
            'favorited' => 'sometimes|boolean'
        ];
    }

    protected function favorited(bool $onlyFavorited): void
    {
        if (!$onlyFavorited || !auth()->check()) {
            return;
        }

        $this->builder->whereHas(
            'favorites',
            fn ($query) => $query->where('user_id', auth()->id())
        );
    }
}
