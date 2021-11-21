<?php

namespace Database\Factories\Journal;

use App\Models\Journal\EventSpeaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventSpeakerFactory extends Factory
{
    protected $model = EventSpeaker::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'position' => $this->faker->word
        ];
    }
}
