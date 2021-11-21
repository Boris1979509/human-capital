<?php

namespace App\Http\Controllers\Api\Institution;

use App\Http\Controllers\Controller;
use App\Http\Requests\Institution\InstitutionSettingRequest;
use App\Http\Resources\Institution\InstitutionResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionSetting;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InstitutionSettingController extends Controller
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
     * @param Institution $institution
     * @param InstitutionSettingRequest $request
     * @return JsonResponse|Response|object
     * @throws AuthorizationException
     */
    public function store(Institution $institution, InstitutionSettingRequest $request)
    {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        // Сохраняем настройку
        foreach ($request->getInstitutionSettingData() as $key => $value) {
            InstitutionSetting::updateOrCreate([
                'user_id' => Auth::user()->id,
                'institution_id' => $institution->id,
                'key' => $key,
            ], [
                'value' => $value,
                'type' => is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : null)
            ]);
        }

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Institution $institution
     * @return JsonResponse|Response|object
     */
    public function show(Institution $institution)
    {
        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Institution $institution
     * @param InstitutionSetting $setting
     * @param Request $request
     * @return JsonResponse|Response|object
     * @throws AuthorizationException
     */
    public function update(Institution $institution, InstitutionSetting $setting, Request $request)
    {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        // Сохраняем настройку
        $setting->update(['value' => $request['value']]);

        return (new InstitutionResource($institution))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Institution $institution
     * @param InstitutionSetting $setting
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Institution $institution, InstitutionSetting $setting): JsonResponse
    {
        // Проверка прав на редактирование
        $this->authorize('manage', [Institution::class, $institution]);

        // Удаляем настрйоку
        $setting->delete();
        return response()->json([], Response::HTTP_OK);
    }
}
