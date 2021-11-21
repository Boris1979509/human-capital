<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavoritesController extends Controller
{
    public function count(): JsonResponse
    {
        $userId = auth()->id();

        $favoritesCount = Favorite::where('user_id', $userId)->count();

        return response()->json($favoritesCount);
    }

    public function create(Request $request, string $favoriteableType, int $favoriteableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        Favorite::unguard();

        Favorite::updateOrCreate([
            'favoriteable_type' => $favoriteableType,
            'favoriteable_id' => $favoriteableId,
            'user_id' => auth()->id(),
        ]);

        return response()->json(null, Response::HTTP_CREATED);
    }

    public function delete(Request $request, string $favoriteableType, int $favoriteableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        Favorite::unguard();

        Favorite::where([
            'favoriteable_type' => $favoriteableType,
            'favoriteable_id' => $favoriteableId,
            'user_id' => auth()->id(),
        ])->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    private function ensureRouteParametersAreCorrect(Request $request): void
    {
        $favoriteableType = $request->route('favoriteableType');
        $favoriteableId = $request->route('favoriteableId');
        $availableFavoritableTypes = config('app.favoriteable', []);

        if (!array_key_exists($favoriteableType, $availableFavoritableTypes)) {
            abort(404);
        }

        $favoriteableClass = $availableFavoritableTypes[$favoriteableType];

        if (!$favoriteableClass::where('id', $favoriteableId)->exists()) {
            abort(404);
        }
    }
}
