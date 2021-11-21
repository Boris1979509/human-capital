<?php

namespace App\Http\Resources\Employer;

use App\Models\Profession;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Profession
 */
class ProfessionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
