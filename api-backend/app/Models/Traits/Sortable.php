<?php

namespace App\Models\Traits;

use App\Http\Requests\SortRequest;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeSort(Builder $query, SortRequest $sorter): Builder
    {
        return $sorter->apply($query);
    }
}
