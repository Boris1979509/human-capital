<?php

namespace App\Http\Controllers\Api\Institution;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institution\InstitutionEmployeeCreateOrUpdateRequest;
use App\Http\Resources\Institution\InstitutionEmployeeResource;
use App\Http\Resources\Institution\InstitutionResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionEmployee;
use App\Models\TemporaryUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class InstitutionEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Institution  $institution
     * @return AnonymousResourceCollection
     */
    public function index(Institution $institution): AnonymousResourceCollection
    {
        return InstitutionEmployeeResource::collection($institution->employees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Institution  $institution
     * @param  InstitutionEmployeeCreateOrUpdateRequest  $request
     * @return JsonResponse|Response|object
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Institution $institution, InstitutionEmployeeCreateOrUpdateRequest $request)
    {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        // Заводим сотрудника
        /** @var InstitutionEmployee $employee */
        $employee = $institution->employees()->create($request->getInstitutionEmployeeData());

        $this->updateEmployeeAvatar($employee, $request);

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  Institution  $institution
     * @param  InstitutionEmployee  $employee
     * @return JsonResponse|Response|object
     */
    public function show(Institution $institution, InstitutionEmployee $employee)
    {
        return (new InstitutionEmployeeResource($employee))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Institution  $institution
     * @param  InstitutionEmployee  $employee
     * @param  InstitutionEmployeeCreateOrUpdateRequest  $request
     * @return JsonResponse|Response|object
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(
        Institution $institution,
        InstitutionEmployee $employee,
        InstitutionEmployeeCreateOrUpdateRequest $request
    ) {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        // Редактируем сотрудника
        $employee->update($request->getInstitutionEmployeeData());

        $this->updateEmployeeAvatar($employee, $request);

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Institution  $institution
     * @param  InstitutionEmployee  $employee
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Institution $institution, InstitutionEmployee $employee): JsonResponse
    {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        // Удаляем сотрудника
        $employee->delete();
        return response()->json([], Response::HTTP_OK);
    }

    private function updateEmployeeAvatar(
        InstitutionEmployee $employee,
        InstitutionEmployeeCreateOrUpdateRequest $request
    ): void {
        if ($request->missing('avatar')) {
            $employee->clearMediaCollection('avatar');
            return;
        }
        if ($request->get('avatar') !== optional($employee->getFirstMedia('avatar'))->id) {
            TemporaryUpload::attachMediaToModel($request->get('avatar'), $employee, 'avatar');
        }
    }
}
