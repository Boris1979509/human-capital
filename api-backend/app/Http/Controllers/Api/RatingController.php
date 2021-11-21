<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRatingRequest;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RatingController extends Controller
{
    public function create(CreateRatingRequest $request, string $rateableType, int $rateableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        /** @var User $user */
        $user = auth()->user();

        Rating::updateOrCreate(
            [
                'rateable_type' => $rateableType,
                'rateable_id' => $rateableId,
                'user_id' => $user->id,
            ],
            [
                'type' => $user->type,
                'rating' => $request->get('rating')
            ]
        );

        return response()->json(null, Response::HTTP_CREATED);
    }

    private function ensureRouteParametersAreCorrect(Request $request): void
    {
        $rateableType = $request->route('rateableType');
        $rateableId = $request->route('rateableId');
        $availableRateableTypes = config('app.rateable', []);

        if (!array_key_exists($rateableType, $availableRateableTypes)) {
            abort(404);
        }

        $rateableClass = $availableRateableTypes[$rateableType];

        if (!$rateableClass::where('id', $rateableId)->exists()) {
            abort(404);
        }
    }
}
