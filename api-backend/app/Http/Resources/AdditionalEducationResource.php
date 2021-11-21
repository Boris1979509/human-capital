<?php

namespace App\Http\Resources;

use App\Models\UserPersonal\UserAdditionalEducation;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin UserAdditionalEducation */
class AdditionalEducationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'organization' => $this->organization,
            'year_end' => $this->year_end,
        ];
    }
}
