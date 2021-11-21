<?php

namespace App\Http\Resources;

use App\Models\Journal\ContentType;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarEventResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->calendareable->id ?? $this->id,
            'type' => $this->calendareable_type ?? 'content',
            'title' => $this->title ?? $this->calendareable->title ?? $this->calendareable->name ?? null,
            'phone' => $this->phone ?? $this->calendareable->phone ?? null,
            'address' => $this->address ?? $this->calendareable->address ?? null,
            'cover' =>
                ($this->calendareable_type) ?
                new MediaResource($this->calendareable->getFirstMedia('cover'))
                :
                (new MediaResource($this->getFirstMedia('cover')) ?? null),
            'published_at' => $this->published_at ?? $this->calendareable->published_at ?? null,
            'started_at' => $this->date_start ?? $this->calendareable->date_start ?? null,
            'stopped_at' => $this->date_end ?? $this->calendareable->date_end ?? null,
            'start' => $this->date_start ?? $this->calendareable->date_start ?? null,
            'backgroundColor' => null,
        ];
    }
}
