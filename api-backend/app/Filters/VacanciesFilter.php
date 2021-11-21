<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class VacanciesFilter extends FilterRequest
{
    protected function getRules(): array
    {
        return [
            'employer_id' => 'sometimes|integer|exists:employers,id',
            'employer' => 'sometimes|string',
            'q' => 'sometimes|string',
            'city_id' => 'sometimes|array',
            'profession_id' => 'sometimes|array',
            'published_from' => 'sometimes|date',
            'published_to' => 'sometimes|date',
            'skills' => 'sometimes|array',
            'salary_min' => 'sometimes|integer',
            'internship' => 'sometimes|boolean'
        ];
    }

    protected function employerId(int $employerId): void
    {
        $this->builder->where('employer_id', $employerId);
    }

    protected function employer(string $employer): void
    {
        $this->builder->whereHas('employer', function (Builder $query) use ($employer) {
            return $query->where('name', 'ilike', "%$employer%");
        });
    }

    protected function q(string $query): void
    {
        $this->builder->where('name', 'like', "%$query%");
    }

    protected function cityId(array $cityIds): void
    {
        $this->builder->whereIn('city_id', $cityIds);
    }

    protected function professionId(array $professionsIds): void
    {
        $this->builder->whereIn('profession_id', $professionsIds);
    }

    protected function publishedFrom(string $date): void
    {
        $this->builder->where('created_at', '>=', $date);
    }

    protected function publishedTo(string $date): void
    {
        $this->builder->where('created_at', '<=', $date);
    }

    protected function skills(array $skills): void
    {
        foreach ($skills as $skill) {
            $this->builder->whereJsonContains('skills', $skill);
        }
    }

    protected function salaryMin(int $salary): void
    {
        $this->builder->where('salary_min', '>=', $salary);
    }

    protected function internship(bool $isInternship): void
    {
        $this->builder->where('is_internship', $isInternship);
    }
}
