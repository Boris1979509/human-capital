<?php

namespace Database\Factories\Employer;

use App\Models\Employer\VacancyResponse;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyResponseFactory extends Factory
{
    protected $model = VacancyResponse::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vacancy_id' => Vacancy::factory(),
            'cv_type' => VacancyResponse::CV_TYPE_GENERATED,
        ];
    }
}
