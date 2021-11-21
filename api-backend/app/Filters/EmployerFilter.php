<?php

namespace App\Filters;

class EmployerFilter extends FilterRequest
{
    protected function getRules(): array
    {
        return [
            'q' => 'sometimes|string',
            'branch_id' => 'sometimes|array',
            'city_id' => 'sometimes|array'
        ];
    }

    protected function q(string $value): void
    {
        $this->builder->where('name', 'ILIKE', "%$value%");
    }

    protected function branchId(array $branchIds): void
    {
        $this->builder->whereIn('branch_id', $branchIds);
    }

    protected function cityId(array $cityIds): void
    {
        $this->builder->whereIn('city_id', $cityIds);
    }
}
