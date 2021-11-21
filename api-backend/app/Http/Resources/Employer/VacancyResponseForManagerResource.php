<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use App\Models\Employer\VacancyResponse;
use App\Models\Resume\Resume;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResponseForManagerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'covering_letter' => $this->covering_letter,
            'cv_type' => $this->cv_type,
            'cv_file_id' => $this->cv_file_id,
            'created_at' => $this->created_at,
            'cv' => [
                'type' => $this->cv_type,
                'data' => $this->getCvResource(),
            ],
            'applicant' => new UserResource($this->applicant),
            'invite' => $this->invite,
            'rejection' => $this->rejection,
        ];
    }

    public function getCvResource()
    {
        switch ($this->cv_type) {
            case VacancyResponse::CV_TYPE_UPLOADED:
                return new MediaResource($this->cvFile);
            case VacancyResponse::CV_TYPE_GENERATED:
                return (new Resume($this->applicant))->toArray();
            default:
                return null;
        }
    }
}
