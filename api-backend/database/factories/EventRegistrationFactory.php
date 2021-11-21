<?php

namespace Database\Factories;

use App\Models\EventRegistration;
use App\Models\Journal\Content;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventRegistrationFactory extends Factory
{
    protected $model = EventRegistration::class;

    public function definition(): array
    {
        return [
            'event_id' => Content::factory()->event(),
            'user_id' => User::factory(),
            'status' => EventRegistration::STATUS_PENDING,
            'fields' => ['some' => 'field']
        ];
    }
}
