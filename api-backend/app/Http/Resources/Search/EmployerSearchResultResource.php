<?php

namespace App\Http\Resources\Search;

use ElasticScoutDriverPlus\QueryMatch;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin QueryMatch */
class EmployerSearchResultResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->model()->id,
            'title' => optional($this->highlight())->getSnippets('name')[0] ?? $this->model()->name,
            'text' => $this->model()->description,
            'type' => 'employer'
        ];
    }
}
