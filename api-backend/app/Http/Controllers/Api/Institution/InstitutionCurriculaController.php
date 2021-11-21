<?php

namespace App\Http\Controllers\Api\Institution;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institution\CurriculumCreateOrUpdateRequest;
use App\Http\Resources\Institution\CurriculumDetailedResource;
use App\Http\Resources\Institution\CurriculumResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class InstitutionCurriculaController extends Controller
{
    public function index(Institution $institution): AnonymousResourceCollection
    {
        $this->authorize('manage', [InstitutionCurriculum::class, $institution]);

        return CurriculumResource::collection(
            $institution->curricula()->with(['institution', 'curriculumType'])->get()
        );
    }

    public function show(Institution $institution, InstitutionCurriculum $curriculum): CurriculumDetailedResource
    {
        $this->authorize('manage', [InstitutionCurriculum::class, $institution]);

        return new CurriculumDetailedResource($curriculum);
    }

    /**
     * @param  Institution  $institution
     * @param  CurriculumCreateOrUpdateRequest  $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function create(Institution $institution, CurriculumCreateOrUpdateRequest $request): JsonResponse
    {
        $this->authorize('manage', [InstitutionCurriculum::class, $institution]);

        /**
         * @var InstitutionCurriculum $curriculum
         */
        $curriculum = $institution->curricula()->create($request->getCurriculumData());
        $curriculum->directions()->sync($request->get('directions'));

        return response()->json(new CurriculumResource($curriculum), Response::HTTP_CREATED);
    }

    /**
     * @param  Institution  $institution
     * @param  InstitutionCurriculum  $curriculum
     * @param  CurriculumCreateOrUpdateRequest  $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(
        Institution $institution,
        InstitutionCurriculum $curriculum,
        CurriculumCreateOrUpdateRequest $request
    ): JsonResponse {
        $this->authorize('manage', [InstitutionCurriculum::class, $institution]);

        $curriculum->update($request->getCurriculumData());
        $curriculum->directions()->sync($request->get('directions'));

        return response()->json(new CurriculumResource($curriculum), Response::HTTP_OK);
    }

    /**
     * @param  Institution  $institution
     * @param  InstitutionCurriculum  $curriculum
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function delete(Institution $institution, InstitutionCurriculum $curriculum): JsonResponse
    {
        $this->authorize('manage', [InstitutionCurriculum::class, $institution]);

        $curriculum->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
