<?php

namespace Database\Factories;

use App\Models\Dictionary;
use Illuminate\Database\Eloquent\Factories\Factory;

class DictionaryFactory extends Factory
{
    protected $model = Dictionary::class;

    public function definition(): array
    {
        return [
            'option' => $this->faker->word,
            'approved' => true
        ];
    }
}
