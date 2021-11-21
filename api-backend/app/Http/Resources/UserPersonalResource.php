<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserPersonalResource extends JsonResource
{
    public function toArray($request): array
    {
        if ($this->type === User::TYPE_USER_PERSONAL) {
            return array_merge(parent::toArray($request), [
                'progress' => $this->getProgress(),
                'avatar' => new MediaResource($this->getFirstMedia('avatar')),
                'personal' => $this->personal ?? null,
                'education' => $this->education ? EducationResource::collection($this->education) : null,
                'additional_education' => $this->additionalEducation ? AdditionalEducationResource::collection($this->additionalEducation) : null,
                'jobs' => $this->jobs ?? null,
                'job_files' => MediaResource::collection($this->getMedia('job')),
            ]);
        }

        return parent::toArray($request);
    }
}
