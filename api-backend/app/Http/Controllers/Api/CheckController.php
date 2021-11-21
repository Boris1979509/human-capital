<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @return JsonResource|JsonResponse
     */
    public function index()
    {
        $email = request('email');
        if ($email) {
            $user = User::with(array('personal'=>function ($query) {
                $query->select('user_id', 'first_name', 'middle_name', 'last_name');
            }))
                ->select('id', 'type', 'email')
                ->where('email', $email)
                ->first();
            if ($user) {
                return new JsonResource($user);
            }
        }

        return response()->json([
            'message' => 'Not Found!',
        ], 404);
    }
}
