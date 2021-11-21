<?php

namespace App\Filters;

use App\Models\Journal\ContentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class ContentFilter extends FilterRequest
{
    public const AUDIENCE_MAP = [
        'for_children', // для детей
        'for_entrant', // для абитуриентов
        'for_adult', // для взрослых
    ];

    protected function getRules(): array
    {
        return [
            'filter' => ['sometimes', Rule::in(self::AUDIENCE_MAP)],
            'institution_id' => ['sometimes', 'integer', 'exists:institutions,id'],
            'type' => ['sometimes', 'integer', Rule::in(array_keys(ContentType::getAll()))],
            'limit' => 'sometimes|integer',
            'favorited' => 'sometimes|boolean',
            'employer_id' => 'sometimes|integer|exists:employers,id',
            'from_employers' => 'sometimes|boolean',
        ];
    }

    protected function institutionId(int $institutionId): void
    {
        $this->builder->where('institution_id', $institutionId);
    }

    protected function type(int $type): void
    {
        $this->builder->where('type', $type);
    }

    protected function limit(int $limit): void
    {
        $this->builder->limit($limit);
    }

    protected function filter($value): void
    {
        $this->builder->where(function ($q) use ($value) {
            $q->whereHas(
                'targetAudience',
                function ($builder) use ($value) {
                    $builder->whereIn('id', config('app.content_audience.'.$value));
                }
            )
                ->orWhere('type', '<>', ContentType::EVENT);
        });
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

    protected function employerId(int $employerId): void
    {
        $this->builder->where('user_id', $employerId);
    }

    protected function fromEmployers(bool $isFromEmployersOnly): void
    {
        $this->builder->whereHas('author', function (Builder $query) use ($isFromEmployersOnly) {
            $operator = $isFromEmployersOnly ? '=' : '!=';
            $query->where('type', $operator, User::TYPE_USER_EMPLOYER);
        });
    }
}
