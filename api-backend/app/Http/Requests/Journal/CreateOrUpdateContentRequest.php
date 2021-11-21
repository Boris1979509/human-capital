<?php

namespace App\Http\Requests\Journal;

use App\Models\Journal\ContentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CreateOrUpdateContentRequest extends FormRequest
{
    public function rules(): array
    {
        return array_merge(
            $this->getArticleRules(),
            $this->getEventRules(),
            $this->getSpeakersRules()
        );
    }

    private function getArticleRules(): array
    {
        return [
            'title' => 'required|string',
            'slug' => 'sometimes|nullable|string',
            'text' => 'required|string',
            'type' => [
                'required',
                'integer',
                Rule::in(array_keys(ContentType::getAll()))
            ],
            'comments_enabled' => 'sometimes|nullable|boolean',
            'is_published' => 'sometimes|nullable|boolean',
            'reading_time' => 'sometimes|nullable|string',
            'cover' => 'sometimes|nullable|integer|exists:media,id',
        ];
    }

    private function getEventRules(): array
    {
        return [
            'date_start' => "exclude_unless:type,3|required|date",
            'address' => 'exclude_unless:type,3|required|string',
            'coords' => 'exclude_unless:type,3|sometimes|nullable|array',
            'date_end' => "sometimes|nullable|date",
            'time_start' => 'sometimes|nullable|string',
            'phone' => 'exclude_unless:type,3|required|string',
            'tags' => 'exclude_unless:type,3|required|array',
            'target_audience' => 'exclude_unless:type,3|required|array',
            'target_audience.*' => 'exclude_unless:type,3|required|integer|exists:dictionaries,id',
            'participants_age' => 'exclude_unless:type,3|required|array',
            'participants_age.*' => 'exclude_unless:type,3|required|integer|exists:dictionaries,id',
            'is_registration_required' => "exclude_unless:type,3|nullable|boolean",
            'registration_available_till' => "exclude_unless:type,3|nullable|date",
            'available_registration_slots' => "exclude_unless:type,3|nullable|integer",
            'registration_fields' => "exclude_unless:type,3|nullable|array",
            'registration_questions' => "exclude_unless:type,3|nullable|array",
            'is_registration_auto' => "exclude_unless:type,3|nullable|boolean",
            'registration_auto_period' => "exclude_unless:type,3|nullable|string",
            'is_registration_reminders_enabled' => "exclude_unless:type,3|nullable|boolean",
            'registration_reminder_periods' => "exclude_unless:type,3|nullable|array",

        ];
    }

    private function getSpeakersRules(): array
    {
        return [
            'speakers' => 'exclude_unless:type,3|sometimes|array',
            'speakers.*.name' => 'required|string',
            'speakers.*.position' => 'required|string',
            'speakers.*.avatar' => 'sometimes|nullable|integer|exists:media,id',
        ];
    }

    public function getContentData(): array
    {
        if ($this->get('type') === ContentType::EVENT) {
            return $this->getEventData();
        }
        return $this->getArticleData();
    }

    private function getArticleData(): array
    {
        $data = $this->only(array_keys($this->getArticleRules()));
        $data['slug'] = Str::slug($this->get('title'));

        return $data;
    }

    private function getEventData(): array
    {
        return (array_merge(
            $this->getArticleData(),
            $this->only([
                'date_start',
                'address',
                'coords',
                'date_end',
                'time_start',
                'phone',
                'tags',
                'target_audience',
                'participants_age',
                'is_registration_required',
                'registration_available_till',
                'available_registration_slots',
                'registration_fields',
                'registration_questions',
                'is_registration_auto',
                'registration_auto_period',
                'is_registration_reminders_enabled',
                'registration_reminder_periods',
            ])
        ));
    }

    public function getSpeakersData()
    {
        return $this->get('speakers');
    }
}
