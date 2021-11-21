<?php

namespace App\Models\Traits;

use App\Filters\FilterRequest;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $query, FilterRequest $filters): Builder
    {
        return $filters->apply($query);
    }
}
