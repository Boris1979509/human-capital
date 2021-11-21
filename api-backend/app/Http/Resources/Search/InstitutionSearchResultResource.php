<?php

namespace App\Http\Resources\Search;

use ElasticScoutDriverPlus\QueryMatch;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin QueryMatch */
class InstitutionSearchResultResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->model()->id,
            'title' => optional($this->highlight())->getSnippets('full_name')[0] ?? $this->model()->full_name,
            'text' => $this->model()->description,
            'type' => 'institution'
        ];
    }
}
