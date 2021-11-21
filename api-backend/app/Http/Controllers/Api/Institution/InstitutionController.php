<?php

namespace App\Http\Controllers\Api\Institution;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institution\InstitutionCreateOrUpdateRequest;
use App\Http\Resources\CalendarEventResource;
use App\Http\Resources\Institution\InstitutionForAutocompleteResource;
use App\Http\Resources\Institution\InstitutionResource;
use App\Models\Institution\Institution;
use App\Models\InstitutionRoles;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use App\Models\University;
use DB;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return InstitutionResource::collection(Auth::user()->managedInstitutions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  InstitutionCreateOrUpdateRequest  $request
     * @return JsonResponse|Response|object
     * @throws Throwable
     */
    public function store(InstitutionCreateOrUpdateRequest $request)
    {
        $institution = DB::transaction(function () use ($request) {
            // Создаем организацию
            $institution = Institution::create(array_merge(
                $request->getInstitutionData(),
                ['university_id' => University::initialize($request['full_name'])->id]
            ));

            // Пользователь, который создал делаем владельцем
            $institution->managers()->save(Auth::user(), ['role' => InstitutionRoles::OWNER]);

            return $institution;
        });


        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  Institution  $institution
     * @return JsonResponse|Response|object
     */
    public function show(Institution $institution)
    {
        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Institution  $institution
     * @param  InstitutionCreateOrUpdateRequest  $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Institution $institution, InstitutionCreateOrUpdateRequest $request): JsonResponse
    {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        // Обновляем организацию
        $institution->update(array_merge(
            $request->getInstitutionData(),
            ['university_id' => University::initialize($request['full_name'])->id]
        ));

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Institution  $institution
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Institution $institution): JsonResponse
    {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        // Удаляем организацию
        $institution->delete();
        return response()->json([], Response::HTTP_OK);
    }

    public function avatar(Institution $institution)
    {
        $institution->addMediaFromRequest('file')->toMediaCollection('avatar');

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function avatarDelete(Institution $institution)
    {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        $institution->clearMediaCollection('avatar');

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function autocomplete(Request $request): AnonymousResourceCollection
    {
        $searchQuery = $request->get('name');
        $institutions = Institution::where('full_name', 'ILIKE', "%$searchQuery%")
            ->orWhere('short_name', 'ILIKE', "%$searchQuery%")
            ->get();

        return InstitutionForAutocompleteResource::collection($institutions);
    }

    public function calendar(Institution $institution): AnonymousResourceCollection
    {
        $events = Content::where('institution_id', $institution->id)
            ->where('type', ContentType::EVENT)
            ->get();

        return CalendarEventResource::collection($events);
    }
}
