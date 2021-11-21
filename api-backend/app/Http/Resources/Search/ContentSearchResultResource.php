<?php

namespace App\Http\Resources\Search;

use App\Models\Journal\ContentType;
use ElasticScoutDriverPlus\QueryMatch;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin QueryMatch */
class ContentSearchResultResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->model()->id,
            'title' => optional($this->highlight())->getSnippets('title')[0] ?? $this->model()->title,
            'text' => $this->model()->text,
            'type' => ContentType::byId($this->model()->type)
        ];
    }
}
