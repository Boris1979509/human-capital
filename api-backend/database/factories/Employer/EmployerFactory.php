<?php

namespace Database\Factories\Employer;

use App\Models\User;
use App\Models\Employer\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployerFactory extends Factory
{
    protected $model = Employer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'user_id' => User::factory()->create(['type' => User::TYPE_USER_EMPLOYER]),
        ];
    }
}
