<?php

namespace App\Http\Resources\Journal;

use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\Dictionary;
use App\Models\EventRegistration;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use DateTimeInterface;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Content */
class ContentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'published_at' => optional($this->published_at)->format(DateTimeInterface::RFC3339),
            'target_audience' => new DictionaryValueResource(Dictionary::getById($this->target_audience)),
            'type' => [
                'id' => $this->type,
                'name' => ContentType::byId($this->type)
            ],
            'is_published' => $this->is_published,
            'cover' => new MediaResource($this->getFirstMedia('cover')),
            'images' => MediaResource::collection($this->getMedia('images')),
            $this->mergeWhen($this->type === ContentType::EVENT, function () {
                return [
                    'registration_available_till' => $this->registration_available_till,
                    'registrations_count' => $this->registrations_count,
                    'processed_registrations_count' => $this->processed_registrations_count,
                    'status' => optional($this->date_start)->isAfter(now()) ? 'Завершенное' : '-'
                ];
            }),
        ];
    }
}
