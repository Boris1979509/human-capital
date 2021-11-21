<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\MediaResource;
use App\Models\Employer\Employer;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Employer */
class EmployerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'count_employees' => $this->count_employees,
            'is_internship' => $this->is_internship,
            'address' => $this->address,
            'coords' => $this->coords,
            'website' => $this->website,
            'contacts' => $this->contacts,
            'avatar' => new MediaResource($this->getFirstMedia('avatar')),
            'cover' => new MediaResource($this->getFirstMedia('cover')),
            'images' => MediaResource::collection($this->getMedia('images')),
            'settings' => $this->settings,
            'city_id' => $this->city_id,
            'branch_id' => $this->branch_id,
        ];
    }
}
