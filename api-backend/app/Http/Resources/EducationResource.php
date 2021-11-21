<?php

namespace App\Http\Resources;

use App\Models\UserPersonal\UserEducation;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin UserEducation */
class EducationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'edu_degree_id' => $this->edu_degree_id,
            'edu_status_id' => $this->edu_status_id,
            'edu_quality_id' => $this->edu_quality_id,
            'year_begin' => $this->year_begin,
            'year_end' => $this->year_end,
            'specialty' => $this->specialty,
            'document_number' => $this->document_number,
            'document_date' => $this->document_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'files' => MediaResource::collection($this->getMedia('education')),
            'university_id' => $this->university_id ?? null,
            'university' => $this->university ? $this->university->title : null
        ];
    }
}
