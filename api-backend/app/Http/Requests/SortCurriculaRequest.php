<?php

namespace App\Http\Requests;

class SortCurriculaRequest extends SortRequest
{
    protected function getAvailableSorters(): array
    {
        return [
            'published_at',
        ];
    }
}
