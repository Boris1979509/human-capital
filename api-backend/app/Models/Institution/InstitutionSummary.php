<?php


namespace App\Models\Institution;

use Exception;

class InstitutionSummary
{
    public const INSTITUTION_COUNT = 'INSTITUTION_COUNT';
    public const CURRICULUM_COUNT = 'CURRICULUM_COUNT';

    /**
     * @return array[]
     * @throws Exception
     */
    public static function get(): array
    {
        return [
            [
                'id' => self::INSTITUTION_COUNT,
                'count' => Institution::count()
            ],
            [
                'id' => self::CURRICULUM_COUNT,
                'count' => InstitutionCurriculum::count()
            ],
        ];
    }
}
