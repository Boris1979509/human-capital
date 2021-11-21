<?php

namespace Database\Factories;

use App\Models\Dictionary;
use App\Models\Employer\Employer;
use App\Models\Profession;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'profession_id' => Profession::factory(),
            'experience_level' => Dictionary::factory(),
            'employment_type' => Dictionary::factory(),
            'schedule' => Dictionary::factory(),
            'competitions' => $this->faker->words(2),
            'salary_negotiable' => false,
            'is_internship' => false,
            'is_working_address_visible' => false,
            'show_chat' => false,
            'employer_id' => Employer::factory(),
        ];
    }
}
