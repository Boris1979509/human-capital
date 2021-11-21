<?php

namespace App\Http\Resources\Journal;

use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use Illuminate\Http\Resources\Json\JsonResource;
use Mtownsend\ReadTime\ReadTime;

/**
 * @mixin Content
 */
class ContentDetailedResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'date' => $this->published_at,
            'type' => [
                'id' => $this->type,
                'name' => ContentType::byId($this->type)
            ],
            'read_time' => (new ReadTime($this->text))->toArray()['minutes']." мин.",
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_published' => $this->is_published,
            'cover' => new MediaResource($this->getFirstMedia('cover')),
            'images' => MediaResource::collection($this->getMedia('images')),
            'comments_enabled' => $this->comments_enabled,
            'institution' => [
                'id' => optional($this->institution)->id,
                'full_name' => optional($this->institution)->full_name,
                'avatar' => new MediaResource(optional($this->institution)->getFirstMedia('avatar'))
            ],
            'is_favorited' => $this->isFavorited(auth()->id()),
            'is_calendar_entry' => $this->isCalendarEntry(),
            $this->mergeWhen($this->type === ContentType::EVENT, function () {
                return [
                    'date_start' => $this->date_start,
                    'date_end' => $this->date_end,
                    'phone' => $this->phone,
                    'target_audience' => DictionaryValueResource::collection($this->targetAudience),
                    'participants_age' => DictionaryValueResource::collection($this->participantsAge),
                    'address' => $this->address,
                    'coords' => $this->coords,
                    'tags' => $this->tags,
                    'speakers' => SpeakerResource::collection($this->speakers),
                    'is_registration_required' => $this->is_registration_required,
                    'registration_available_till' => $this->registration_available_till,
                    'available_registration_slots' => $this->available_registration_slots,
                    'registration_fields' => $this->registration_fields,
                    'registration_questions' => $this->registration_questions,
                    'is_registration_auto' => $this->is_registration_auto,
                    'registration_auto_period' => $this->registration_auto_period,
                    'is_registration_reminders_enabled' => $this->is_registration_reminders_enabled,
                    'registration_reminder_periods' => $this->registration_reminder_periods,

                ];
            })

        ];
    }
}
