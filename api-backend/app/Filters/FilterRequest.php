<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class FilterRequest
{
    public Request $request;
    protected Builder $builder;

    public function __construct(Request $request)
    {
        $request->validate($this->getRules());
        $this->request = $request;
    }

    abstract protected function getRules(): array;

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        collect($this->getFilters())
            ->each(function ($value, $filter) {
                $camelCaseFilter = (string) Str::of($filter)->camel();
                if (method_exists($this, $camelCaseFilter)) {
                    $this->$camelCaseFilter($value);
                }
            });

        return $this->builder;
    }

    protected function getFilters(): array
    {
        $keys = collect(array_keys($this->getRules()));
        $filteredKeys = $keys->filter(fn ($key) => !Str::contains($key, '*'));
        return $this->request->only($filteredKeys->toArray());
    }
}
