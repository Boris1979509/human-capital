<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dislike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DislikesController extends Controller
{
    public function create(Request $request, string $dislikeableType, int $dislikeableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        Dislike::updateOrCreate([
            'dislikeable_type' => $dislikeableType,
            'dislikeable_id' => $dislikeableId,
            'user_id' => auth()->id(),
        ]);

        return response()->json(null, Response::HTTP_CREATED);
    }

    public function delete(Request $request, string $dislikeableType, int $dislikeableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        Dislike::where([
            'dislikeable_type' => $dislikeableType,
            'dislikeable_id' => $dislikeableId,
            'user_id' => auth()->id(),
        ])->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    private function ensureRouteParametersAreCorrect(Request $request): void
    {
        $dislikeableType = $request->route('dislikeableType');
        $dislikeableId = $request->route('dislikeableId');
        $availableDislikeableTypes = config('app.dislikeable', []);

        if (!array_key_exists($dislikeableType, $availableDislikeableTypes)) {
            abort(404);
        }

        $dislikeableClass = $availableDislikeableTypes[$dislikeableType];

        if (!$dislikeableClass::where('id', $dislikeableId)->exists()) {
            abort(404);
        }
    }
}
