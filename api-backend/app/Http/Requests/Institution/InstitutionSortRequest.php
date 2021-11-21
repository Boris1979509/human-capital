<?php

namespace App\Http\Requests\Institution;

use App\Http\Requests\SortRequest;

class InstitutionSortRequest extends SortRequest
{
    protected function getAvailableSorters(): array
    {
        return [
            'id',
        ];
    }
}
