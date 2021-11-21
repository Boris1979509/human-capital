<?php


namespace App\Http\Controllers\Api\Employer;

use App\Filters\VacanciesFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\CreateOrUpdateVacancyRequest;
use App\Http\Requests\Employer\VacancyResponseCreateRequest;
use App\Http\Resources\Employer\VacancyDetailedResource;
use App\Http\Resources\Employer\VacancyPublicCardResource;
use App\Http\Resources\Employer\VacancyResponseForManagerResource;
use App\Http\Resources\Employer\VacancyResponseForUserResource;
use App\Models\Employer\Employer;
use App\Models\Employer\VacancyResponse;
use App\Models\User;
use App\Models\Vacancy;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserVacanciesResponsesController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = auth()->user();

        $responses = $user->responses()
            ->where('deleted_by_user', false)
            ->with(['vacancy', 'vacancy.employer'])
            ->get();

        return VacancyResponseForUserResource::collection($responses);
    }

    public function create(VacancyResponseCreateRequest $request, Vacancy $vacancy): JsonResponse
    {
        if ($vacancy->isUserApplied(auth()->id())) {
            return response()->json('Вы уже откликнулись на эту вакансию', Response::HTTP_FORBIDDEN);
        }

        $vacancy->responses()->create($request->getVacancyResponseData());

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(VacancyResponse $response): JsonResponse
    {
        $this->authorize('hideFromUser', [VacancyResponse::class, $response]);

        $response->hideFromUser();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
