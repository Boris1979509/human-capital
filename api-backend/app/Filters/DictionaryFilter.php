<?php

namespace App\Filters;

use App\Models\Dictionary;

class DictionaryFilter extends FilterRequest
{
    protected function getRules(): array
    {
        return [
            'type' => 'sometimes|regex:/^[^!,][\w\_,]*$/',
            'ids' => 'sometimes|string'
        ];
    }

    protected function type(string $value): void
    {
        $types = collect(explode(',', $value));
        $availableTypes = collect(Dictionary::NAMING);
        $filteredTypes = $availableTypes->intersect($types);

        $this->builder->whereIn('type', $filteredTypes->keys());
    }

    protected function ids(string $ids): void
    {
        $idsArray = explode(',', $ids);
        $this->builder->whereIn('id', $idsArray);
    }
}
