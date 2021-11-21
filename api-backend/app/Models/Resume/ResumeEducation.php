<?php

namespace App\Models\Resume;

use App\Models\UserPersonal\UserEducation;

class ResumeEducation
{
    protected UserEducation $education;

    public function __construct(UserEducation $education)
    {
        $this->education = $education;
    }

    public function getQuality(): ?string
    {
        return optional($this->education->quality)->option;
    }

    public function getUniversity(): ?string
    {
        return $this->education->university->title;
    }

    public function getSpecialty(): ?string
    {
        return $this->education->specialty;
    }

    public function getYear(): ?string
    {
        return $this->education->year_end;
    }

    public function toArray(): array
    {
        return [
            'quality' => $this->getQuality(),
            'year' => $this->getYear(),
            'university' => $this->getUniversity(),
            'specialty' => $this->getSpecialty()
        ];
    }
}
