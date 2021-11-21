<?php

namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

class UniversityFactory extends Factory
{
    protected $model = University::class;

    public function definition(): array
    {
        return [
            'city_id' => $this->faker->randomDigit,
            'title' => $this->faker->name,
            'alternative' => $this->faker->name
        ];
    }
}
