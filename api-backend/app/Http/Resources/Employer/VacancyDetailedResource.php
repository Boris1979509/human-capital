<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\CityResource;
use App\Models\Vacancy;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Vacancy
 */
class VacancyDetailedResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profession_id' => $this->profession_id,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'salary_negotiable' => $this->salary_negotiable,
            'experience_level' => $this->experience_level,
            'employment_type' => $this->employment_type,
            'schedule' => $this->schedule,
            'is_internship' => $this->is_internship,
            'competitions' => $this->competitions,
            'skills' => $this->skills,
            'responsibilities' => $this->responsibilities,
            'requirements' => $this->requirements,
            'conditions' => $this->conditions,
            'description' => $this->description,
            'city_id' => $this->city_id,
            'working_address' => $this->working_address,
            'coords' => $this->coords,
            'is_working_address_visible' => $this->is_working_address_visible,
            'show_chat' => $this->show_chat,
            'created_at' => $this->created_at
        ];
    }
}
