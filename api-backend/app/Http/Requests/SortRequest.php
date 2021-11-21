<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Класс для сортировки выдачи моделей
 *
 * Формат параметра сортировки = sort=[field, -field]
 * Знак минуса обозначает сортировку по убыванию, отсутствие знака - по возрастанию
 *
 * @property Request $request
 * @property Builder $builder
 */
abstract class SortRequest
{
    protected Request $request;
    protected Builder $builder;

    abstract protected function getAvailableSorters(): array;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $this->getSortParams()->each(function (array $sortParam) {
            $camelCaseParam = (string) Str::of($sortParam['field'])->camel();
            if (method_exists($this, $camelCaseParam)) {
                $this->$camelCaseParam($sortParam['direction']);
            } else {
                $this->builder->orderBy($sortParam['field'], $sortParam['direction']);
            }
        });

        return $this->builder;
    }

    public function getSortParams(): Collection
    {
        $this->request->validate(['order_by' => 'sometimes|string']);

        $sortFields = collect(explode(',', $this->request->get('order_by')));

        return
            $sortFields
                ->map(function ($field) {
                    $sortField = $this->getFieldWithoutSign($field);
                    $direction = $this->getDirectionFromSign($field);
                    return [
                        'direction' => $direction,
                        'field' => $sortField
                    ];
                })
                ->filter(function (array $field) {
                    return $this->fieldIsAvailable($field['field']);
                });
    }

    private function getDirectionFromSign(string $field): string
    {
        return $this->sortDirectionIsDescending($field) ? 'desc' : 'asc';
    }

    private function getFieldWithoutSign(string $field): string
    {
        if ($this->sortDirectionIsDescending($field)) {
            return Str::substr($field, 1);
        }

        return $field;
    }

    private function sortDirectionIsDescending(string $field): bool
    {
        return Str::startsWith($field, '-');
    }

    private function fieldIsAvailable(string $sortField): bool
    {
        return in_array($sortField, $this->getAvailableSorters(), true);
    }
}
