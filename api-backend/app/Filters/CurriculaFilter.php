<?php

namespace App\Filters;

use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class CurriculaFilter extends FilterRequest
{
    public const FILTER_GROUPS = [
        'for_entrant', // для абитуриентов
        'for_children', // для детей
        'for_adult', // для взрослых
    ];

    protected function getRules(): array
    {
        return [
            'filter' => ['sometimes', Rule::in(self::FILTER_GROUPS)],
            'max_cost' => 'sometimes|integer',
            'institution_id' => 'sometimes|integer|exists:institutions,id',
            'edu_form' => 'sometimes|integer|exists:dictionaries,id',
            'limit' => 'sometimes|integer',
            'favorited' => 'sometimes|boolean'
        ];
    }

    protected function getFilters(): array
    {
        return array_merge(parent::getFilters(), ['filterByLearningOptions' => null]);
    }

    public function filterByLearningOptions(): void
    {
        if ($this->hasFiltersByLearningOptions()) {
            $auditoryId = config("app.dictionaries.".$this->request->get('filter'));
            $this->builder->whereIn('id', function (Builder $query) use ($auditoryId) {
                $query->select('id')
                    ->fromRaw("institution_curricula, jsonb_array_elements(institution_curricula.learning_options) as lo")
                    ->when(
                        $auditoryId,
                        fn (Builder $query) => $query->whereRaw(
                            "lo ->> 'auditory' = ?",
                            [$auditoryId]
                        )
                    )
                    ->when(
                        $this->request->has('edu_form'),
                        fn (Builder $query) => $query->whereRaw(
                            "lo ->> 'edu_form' = ?",
                            [$this->request->get('edu_form')]
                        )
                    )
                    ->when(
                        $this->request->has('max_cost'),
                        fn (Builder $query) => $query->whereRaw(
                            "(lo ->> 'cost')::int <= ?::int",
                            [$this->request->get('max_cost')]
                        )
                    );
            });
        }
    }

    protected function institutionId(int $institutionId): void
    {
        $this->builder->where('institution_id', $institutionId);
    }

    private function hasFiltersByLearningOptions(): bool
    {
        return $this->request->hasAny(['filter', 'max_cost', 'edu_form']);
    }

    protected function limit(int $limit): void
    {
        $this->builder->limit($limit);
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
