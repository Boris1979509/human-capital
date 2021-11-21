<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\CityResource;
use App\Http\Resources\DictionaryValueResource;
use App\Models\Dictionary;
use App\Models\Vacancy;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Vacancy
 */
class VacancyDetailedPublicResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profession_id' => new ProfessionResource($this->profession),
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'salary_negotiable' => $this->salary_negotiable,
            'experience_level' => new DictionaryValueResource(Dictionary::getById($this->experience_level)),
            'employment_type' => new DictionaryValueResource(Dictionary::getById($this->employment_type)),
            'schedule' => new DictionaryValueResource(Dictionary::getById($this->schedule)),
            'is_internship' => $this->is_internship,
            'competitions' => $this->competitions,
            'skills' => $this->skills,
            'responsibilities' => $this->responsibilities,
            'requirements' => $this->requirements,
            'conditions' => $this->conditions,
            'description' => $this->description,
            'city_id' => new CityResource($this->city),
            'working_address' => $this->working_address,
            'coords' => $this->coords,
            'is_working_address_visible' => $this->is_working_address_visible,
            'show_chat' => $this->show_chat,
            'created_at' => $this->created_at,
            'employer' => new EmployerPublicCardResource($this->employer),
            'is_favorited' => $this->isFavorited(auth()->id()),
            'is_user_applied' => $this->when(auth()->check(), function () {
                return $this->isUserApplied(auth()->id());
            })
        ];
    }
}
