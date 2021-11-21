<?php

namespace App\Http\Controllers\Api;

use App\Filters\CurriculaFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\SortCurriculaRequest;
use App\Http\Resources\Curricula\CurriculumDetailedForPublicResource;
use App\Http\Resources\Curricula\CurriculumForPublicResource;
use App\Models\CurriculumSummary;
use App\Models\CurriculumSummaryByInstType;
use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CurriculaController extends Controller
{
    public function index(CurriculaFilter $filter, SortCurriculaRequest $sorter): AnonymousResourceCollection
    {
        $curriculaQuery = InstitutionCurriculum::filter($filter)
            ->published()
            ->sort($sorter)
            ->with(['institution', 'curriculumType'])
            ->distinct();

        $columns = [
            'id',
            'name',
            'learning_options',
            'type_id',
            'institution_id',
            'direction_of_study',
            'is_published',
            'published_at'
        ];

        $curricula = $this->requestIsPaginated()
            ? $curriculaQuery->simplePaginate(
                $this->getPerPage(),
                $columns,
                'page',
                $this->getPage()
            )
            : $curriculaQuery->get($columns);

        return CurriculumForPublicResource::collection($curricula);
    }

    public function show(InstitutionCurriculum $curriculum)
    {
        if (!$curriculum->isPublic()) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return new CurriculumDetailedForPublicResource($curriculum);
    }

    public function similar(
        InstitutionCurriculum $curriculum,
        SortCurriculaRequest $sorter
    ): AnonymousResourceCollection {
        $similarCurricula = InstitutionCurriculum::where('type_id', $curriculum->type_id)
            ->where('id', '<>', $curriculum->id)
            ->sort($sorter)
            ->published()
            ->simplePaginate($this->getPerPage());
        return CurriculumForPublicResource::collection($similarCurricula);
    }

    public function summary(Request $request): JsonResponse
    {
        $request->validate([
            'filter' => Rule::in(CurriculaFilter::FILTER_GROUPS)
        ]);

        $auditory = $request->has('filter') ? config("app.dictionaries.{$request->get('filter')}") : null;
        $institutionId = $request->get('institution_id');
        $summary = new CurriculumSummary($auditory, $institutionId);

        return response()->json($summary->get());
    }

    public function typesSummary(Request $request): AnonymousResourceCollection
    {
        $summary = new CurriculumSummaryByInstType($request->get('inst_type'), $request->get('limit'));
        return $summary->get();
    }
}
