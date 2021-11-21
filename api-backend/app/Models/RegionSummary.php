<?php

namespace App\Models;

use App\Models\Institution\Institution;
use Exception;

class RegionSummary
{
    public const INSTITUTION_SUMMARY_ID = 'INSTITUTION';
    public const VACANCIES_SUMMARY_ID = 'VACANCIES';
    public const SALARY_SUMMARY_ID = 'SALARY';

    /**
     * @return array[]
     * @throws Exception
     */
    public function get(): array
    {
        return [
            [
                'id' => self::INSTITUTION_SUMMARY_ID,
                'count' => $this->getInstitutionCount()
            ],
            [
                'id' => self::VACANCIES_SUMMARY_ID,
                'count' => $this->getVacanciesCount()
            ],
            [
                'id' => self::SALARY_SUMMARY_ID,
                'count' => $this->getAverageSalary()
            ]
        ];
    }

    private function getInstitutionCount(): int
    {
        return Institution::count();
    }

    /**
     * @return int
     * @throws Exception
     */
    private function getVacanciesCount(): int
    {
        //TODO: добавить счетчики вакансий
        return random_int(100, 2000);
    }

    /**
     * @return int
     * @throws Exception
     */
    private function getAverageSalary(): string
    {
        //TODO: добавить среднюю зарплату
        return number_format(random_int(5, 15) * 5000, 0, null, ' ');
    }
}
