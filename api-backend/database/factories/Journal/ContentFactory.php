<?php

namespace Database\Factories\Journal;

use App\Models\Dictionary;
use App\Models\Institution\Institution;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'text' => $this->faker->text,
            'slug' => $this->faker->slug,
            'institution_id' => Institution::factory(),
            'user_id' => User::factory(),
            'is_published' => true,
            'type' => ContentType::NEWS,
        ];
    }

    public function event(): ContentFactory
    {
        return $this->state(function () {
            return [
                'phone' => $this->faker->phoneNumber,
                'date_start' => $this->faker->dateTime->format(DateTimeInterface::RFC3339),
                'date_end' => $this->faker->dateTime->format(DateTimeInterface::RFC3339),
                'type' => ContentType::EVENT,
                'address' => $this->faker->address,
                'coords' => [1, 2],
                'tags' => $this->faker->words(2),
            ];
        });
    }

    public function withSpeakers(): ContentFactory
    {
        return $this->state(function () {
            return [
                'speakers' => [
                    [
                        'name' => $this->faker->name,
                        'position' => $this->faker->word
                    ]
                ]
            ];
        });
    }

    public function withEventData()
    {
        return $this->state(function () {
            return [
                'participants_age' => [Dictionary::factory()->create()->id],
                'target_audience' => [Dictionary::factory()->create()->id]
            ];
        });
    }
}
