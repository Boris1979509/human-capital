<?php

namespace Database\Factories\Selection;

use App\Models\Selection\Selection;
use Illuminate\Database\Eloquent\Factories\Factory;

class SelectionFactory extends Factory
{
    protected $model = Selection::class;

    public function definition(): array
    {
        return [
            "slug" => $this->faker->slug,
            "title" => $this->faker->words(3, true),
            "description" => $this->faker->sentence
        ];
    }
}
