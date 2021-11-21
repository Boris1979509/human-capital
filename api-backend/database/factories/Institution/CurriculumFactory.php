<?php

namespace Database\Factories\Institution;

use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurriculumFactory extends Factory
{
    protected $model = InstitutionCurriculum::class;

    public function definition(): array
    {
        return [
            'institution_id' => Institution::factory(),
            'name' => $this->faker->words(3, true),
            'direction_of_study' => $this->faker->word,
            'description' => $this->faker->sentence,
            'questions_enabled' => true,
            'reviews_enabled' => true,
            'is_admission_additional_exam' => false,
            'is_admission_olympiad' => false,
            'is_admission_exam' => false,
            'is_published' => true,
        ];
    }
}
