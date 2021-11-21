<?php

namespace Database\Factories\Institution;

use App\Models\Institution\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionFactory extends Factory
{
    protected $model = Institution::class;

    public function definition(): array
    {
        return [
            'full_name' => $this->faker->words(3, true),
            'short_name' => $this->faker->word,
            'description' => $this->faker->text,
            'website' => $this->faker->url,
        ];
    }
}
