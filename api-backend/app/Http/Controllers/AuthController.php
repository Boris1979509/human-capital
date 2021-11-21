<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use App\Models\Institution\Institution;
use App\Models\InstitutionRoles;
use App\Models\University;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Создание/регистрация пользователя
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function signup(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = new User([
            'type' => $request->type ?? User::TYPE_USER_PERSONAL,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        switch ($user->type) {

            case User::TYPE_USER_PERSONAL:
                $user->personal()->create([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                ]);
                break;

            case User::TYPE_USER_INSTITUTION:
                // Создаем организацию
                $institution = Institution::create([
                    'inst_type_id' => $request->inst_type_id,
                    'full_name' => $request->full_name,
                    'short_name' => $request->short_name,
                    'city_id' => $request->city_id,
                    'university_id' => University::initialize($request->full_name)->id
                ]);

                // Пользователь, который создал делаем владельцем
                $institution->managers()->save($user, ['role' => InstitutionRoles::OWNER]);
                break;

            case User::TYPE_USER_EMPLOYER:

                $user->employer()->create([
                    'name' => $request->name,
                    'branch_id' => $request->branch_id ??
                    (
                        $request->branch_name ?
                            Dictionary::firstOrCreate([
                                'type' => Dictionary::TYPE_BRANCH,
                                'option' => $request->branch_name
                            ])->id
                            : null
                    ),
                ]);
                break;

        }

        return response()->json([
            'message' => 'Successfully created user!',
        ], 201);
    }

    /**
     * Авторизация и создание токена
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::guard('web')->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        /** @var User $user */
        $user = Auth::guard('web')->user();
        $tokenResult = $user->createToken('UserPersonal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Отзыв токена
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Текущий пользователь
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
