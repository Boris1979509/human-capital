<?php

namespace App\Http\Resources\Institution;

use App\Http\Resources\MediaResource;
use App\Models\Institution\InstitutionEmployee;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin InstitutionEmployee */
class InstitutionEmployeeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'position' => $this->position,
            'approved' => $this->approved,
            'avatar' => new MediaResource($this->getFirstMedia('avatar'))
        ];
    }
}
