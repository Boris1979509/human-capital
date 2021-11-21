<?php

namespace App\Models;

use App\Models\Institution\InstitutionCurriculum;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class CurriculumSummary
{
    protected ?int $auditory;
    protected ?int $institutionId;

    public function __construct(?int $auditory = null, ?int $institutionId = null)
    {
        $this->auditory = $auditory;
        $this->institutionId = $institutionId;
    }

    public function get(): array
    {
        $minAndMaxResult = $this->getMinAndMaxCost();
        $countResult = $this->getCurriculaCount();

        return [
            'MAX_PRICE' => $minAndMaxResult->max,
            'MIN_PRICE' => $minAndMaxResult->min,
            'COUNT' => $countResult
        ];
    }

    protected function getMinAndMaxCost()
    {
        return DB::query()
            ->selectRaw("min((value->>'cost')::int) as min, max((value->>'cost')::int) as max")
            ->fromRaw("institution_curricula, jsonb_array_elements(institution_curricula.learning_options)")
            ->when(
                $this->auditory,
                fn (Builder $query) => $query->whereRaw(
                    "value->>'auditory'=?",
                    [$this->auditory]
                )
            )
            ->when(
                $this->institutionId,
                fn (Builder $query) => $query->whereRaw(
                    'institution_curricula.institution_id = ?',
                    [$this->institutionId]
                )
            )
            ->where('institution_curricula.is_published', true)
            ->get()
            ->first();
    }

    /**
     * @return int
     */
    protected function getCurriculaCount(): int
    {
        return InstitutionCurriculum::when(
            $this->institutionId,
            fn (EloquentBuilder $query) => $query->where('institution_id', $this->institutionId)
        )->published()->count();
    }
}
