<?php

namespace App\Http\Requests\Journal;

use App\Http\Requests\SortRequest;

class SortContentRequest extends SortRequest
{
    protected function getAvailableSorters(): array
    {
        return [
            'published_at',
            'date_start'
        ];
    }
}
