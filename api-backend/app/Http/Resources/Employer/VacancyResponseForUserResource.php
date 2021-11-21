<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VacancyResponseForUserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'vacancy' => new VacancyPublicCardResource($this->vacancy),
            'status' => $this->status,
            'covering_letter' => $this->covering_letter,
            'cv_type' => $this->cv_type,
            'cv_file' => $this->cv_file_id ? new MediaResource(Media::find($this->cv_file_id)) : null,
            'created_at' => $this->created_at,
            'invite' => $this->invite,
            'rejection' => $this->rejection,
        ];
    }
}
