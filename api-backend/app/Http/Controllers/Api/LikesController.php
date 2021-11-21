<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LikesController extends Controller
{
    public function create(Request $request, string $likeableType, int $likeableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        Like::updateOrCreate([
            'likeable_type' => $likeableType,
            'likeable_id' => $likeableId,
            'user_id' => auth()->id(),
        ]);

        return response()->json(null, Response::HTTP_CREATED);
    }

    public function delete(Request $request, string $likeableType, int $likeableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        Like::where([
            'likeable_type' => $likeableType,
            'likeable_id' => $likeableId,
            'user_id' => auth()->id(),
        ])->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    private function ensureRouteParametersAreCorrect(Request $request): void
    {
        $likeableType = $request->route('likeableType');
        $likeableId = $request->route('likeableId');
        $availableLikeableTypes = config('app.likeable', []);

        if (!array_key_exists($likeableType, $availableLikeableTypes)) {
            abort(404);
        }

        $likeableClass = $availableLikeableTypes[$likeableType];

        if (!$likeableClass::where('id', $likeableId)->exists()) {
            abort(404);
        }
    }
}
