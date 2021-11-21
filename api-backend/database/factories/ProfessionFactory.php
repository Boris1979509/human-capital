<?php

namespace Database\Factories;

use App\Models\Profession;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionFactory extends Factory
{
    protected $model = Profession::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
