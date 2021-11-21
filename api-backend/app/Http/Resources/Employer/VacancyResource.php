<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\CityResource;
use App\Models\Vacancy;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Vacancy
 */
class VacancyResource extends JsonResource
{
    /**
     * @throws Exception
     */
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
            'created_at' => $this->created_at,
            'responses_count' => $this->responses_count,
            'views' => views($this->resource)->count(),
        ];
    }
}
