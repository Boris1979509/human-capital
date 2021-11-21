<?php

namespace App\Models;

use App\Http\Resources\CurriculumCountResource;
use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CurriculumSummaryByInstType
{
    protected int $instTypeId;
    protected int $limit;

    public function __construct(int $instTypeId, ?int $limit)
    {
        $this->instTypeId = $instTypeId;
        $this->limit = $limit ?? 5;
    }

    public function get(): AnonymousResourceCollection
    {
        $summary = InstitutionCurriculum::whereHas('institution', function (Builder $query) {
            return $query->where('inst_type_id', $this->instTypeId);
        })
            ->selectRaw('type_id, count(*)')
            ->groupBy('type_id')
            ->orderByDesc('count')
            ->limit($this->limit)
            ->with('curriculumType')
            ->get();

        return CurriculumCountResource::collection($summary);
    }
}
