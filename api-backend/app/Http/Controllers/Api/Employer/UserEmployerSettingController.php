<?php

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserEmployerSettingRequest;
use App\Http\Resources\Employer\EmployerResource;
use App\Models\Employer\Employer;
use App\Models\User;
use App\Models\Employer\UserEmployerSetting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserEmployerSettingController extends Controller
{
    /** @var User $user */
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = Auth::user();
    }

    public function index(Employer $employer)
    {
        return (new EmployerResource($this->user->employer))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Employer  $employer
     * @param  UserEmployerSettingRequest  $request
     * @return JsonResponse|object
     */
    public function store(Employer $employer, UserEmployerSettingRequest $request)
    {
        // Сохраняем настройку
        foreach ($request->getUserEmployerSettingData() as $key => $value) {
            UserEmployerSetting::updateOrCreate([
                'user_id' => $this->user->id,
                'key' => $key,
            ], [
                'value' => $value,
                'type' => is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : null)
            ]);
        }

        return (new EmployerResource($this->user->employer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  Employer  $employer
     * @return JsonResponse|object
     */
    public function show(Employer $employer)
    {
        return (new EmployerResource($employer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Employer  $employer
     * @param  UserEmployerSetting  $setting
     * @param  Request  $request
     * @return JsonResponse|object
     */
    public function update(Employer $employer, UserEmployerSetting $setting, Request $request)
    {
        // Сохраняем настройку
        $setting->update(['value' => $request['value']]);

        return (new EmployerResource($employer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Employer  $employer
     * @param  UserEmployerSetting  $setting
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Employer $employer, UserEmployerSetting $setting): JsonResponse
    {
        // Удаляем настройку
        $setting->delete();
        return response()->json([], Response::HTTP_OK);
    }
}
