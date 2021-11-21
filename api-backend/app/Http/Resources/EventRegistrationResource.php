<?php

namespace App\Http\Resources;

use App\Models\EventRegistration;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin EventRegistration */
class EventRegistrationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'fields' => $this->fields,
            'questions' => $this->questions,
            'status'=>$this->status
        ];
    }
}
