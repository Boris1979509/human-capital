<?php


namespace App\Http\Controllers\Api\Employer;

use App\Filters\VacanciesFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\CreateOrUpdateVacancyRequest;
use App\Http\Resources\Employer\VacancyDetailedPublicResource;
use App\Http\Resources\Employer\VacancyDetailedResource;
use App\Http\Resources\Employer\VacancyResource;
use App\Models\Employer\Employer;
use App\Models\Vacancy;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class VacanciesController extends Controller
{
    public function index(Employer $employer, VacanciesFilter $filter): AnonymousResourceCollection
    {
        $vacancies = $employer->vacancies()
            ->filter($filter)
            ->withCount('responses')
            ->simplePaginate($this->getPerPage());
        return VacancyResource::collection($vacancies);
    }


    public function show(Vacancy $vacancy): VacancyDetailedResource
    {
        $this->authorize('manage', [Vacancy::class, $vacancy]);
        return new VacancyDetailedResource($vacancy);
    }

    public function create(Employer $employer, CreateOrUpdateVacancyRequest $request): VacancyDetailedResource
    {
        $vacancy = $employer->vacancies()->create($request->getVacancyAttributes());

        return new VacancyDetailedResource($vacancy);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(
        CreateOrUpdateVacancyRequest $request,
        Vacancy $vacancy
    ): VacancyDetailedResource {
        $this->authorize('manage', [Vacancy::class, $vacancy]);

        $vacancy->update($request->getVacancyAttributes());

        return new VacancyDetailedResource($vacancy);
    }

    /**
     * @throws AuthorizationException
     * @throws Exception
     */
    public function delete(Vacancy $vacancy): JsonResponse
    {
        $this->authorize('manage', [Vacancy::class, $vacancy]);

        $vacancy->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
