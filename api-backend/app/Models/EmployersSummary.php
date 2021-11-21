<?php

namespace App\Models;

use App\Models\Employer\Employer;

class EmployersSummary
{
    public function get(): array
    {
        return [
            'employers_count' => $this->getEmployersCount(),
            'vacancies_count' => $this->getVacanciesCount(),
        ];
    }

    private function getEmployersCount(): int
    {
        return Employer::count();
    }

    private function getVacanciesCount(): int
    {
        return Vacancy::count();
    }
}
