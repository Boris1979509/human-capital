<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\CityResource;
use App\Models\Vacancy;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Vacancy
 */
class VacancyPublicCardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'city' => new CityResource($this->city),
            'name' => $this->name,
            'is_internship' => $this->is_internship,
            'salary_min' => $this->salary_min,
            'salary_negotiable' => $this->salary_negotiable,
            'employer' => new EmployerPublicCardResource($this->employer),
            'responsibilities' => $this->responsibilities,
            'created_at' => $this->created_at
        ];
    }
}
