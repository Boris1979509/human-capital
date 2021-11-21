<?php

namespace App\Filters;

class InstitutionFilter extends FilterRequest
{
    protected function getRules(): array
    {
        return [
            'city' => 'sometimes|integer',
            'type' => 'sometimes|array',
            'type.*' => 'integer|exists:dictionaries,id',
            'favorited' => 'sometimes|boolean'
        ];
    }

    protected function type(array $ids): void
    {
        $this->builder->whereIn('inst_type_id', $ids);
    }

    protected function city(string $value): void
    {
        $this->builder->where('city_id', '=', $value);
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
