<?php


namespace App\Models;

use Illuminate\Support\Collection;

class RatingCalculator
{
    private const THRESHOLD = 20;

    protected Collection $ratings;
    protected bool $showStudentRating;
    protected bool $showEmployerRating;
    private bool $showInstitutionRating;

    public function __construct(
        Collection $ratings,
        bool $showStudentRating = true,
        bool $showEmployerRating = true,
        bool $showInstitutionRating = true
    ) {
        $this->ratings = $ratings;
        $this->showStudentRating = $showStudentRating;
        $this->showEmployerRating = $showEmployerRating;
        $this->showInstitutionRating = $showInstitutionRating;
    }

    public function calculate(): array
    {
        return [
            'personal' => $this->showStudentRating ? $this->calculateRating($this->personalRatings()) : null,
            'employer' => $this->showEmployerRating ? $this->calculateRating($this->employerRatings()) : null,
            'institution' => $this->showInstitutionRating ? $this->calculateRating($this->institutionRatings()) : null
        ];
    }

    private function personalRatings(): Collection
    {
        return $this->ratings->where('type', User::TYPE_USER_PERSONAL);
    }

    private function employerRatings(): Collection
    {
        return $this->ratings->where('type', User::TYPE_USER_EMPLOYER);
    }

    private function institutionRatings(): Collection
    {
        return $this->ratings->where('type', User::TYPE_USER_INSTITUTION);
    }

    private function calculateRating(Collection $ratings)
    {
        if ($ratings->count() <= self::THRESHOLD) {
            return 5;
        }

        return round($ratings->average('rating'), 1);
    }
}
