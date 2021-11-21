<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionsController extends Controller
{
    public function create(Request $request, string $subscribableType, int $subscribableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        Subscription::updateOrCreate([
            'subscribable_type' => $subscribableType,
            'subscribable_id' => $subscribableId,
            'type' => $request->get('type'),
            'user_id' => auth()->id(),
        ]);

        return response()->json(null, Response::HTTP_CREATED);
    }

    public function delete(Request $request, string $subscribableType, int $subscribableId): JsonResponse
    {
        $this->ensureRouteParametersAreCorrect($request);

        Subscription::where([
            'subscribable_type' => $subscribableType,
            'subscribable_id' => $subscribableId,
            'type' => $request->get('type'),
            'user_id' => auth()->id(),
        ])->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    private function ensureRouteParametersAreCorrect(Request $request): void
    {
        $subscribableType = $request->route('subscribableType');
        $subscribableId = $request->route('subscribableId');
        $availableSubscribableTypes = config('app.subscribable', []);

        if (!array_key_exists($subscribableType, $availableSubscribableTypes)) {
            abort(404);
        }

        $subscribableClass = $availableSubscribableTypes[$subscribableType];

        if (!$subscribableClass::where('id', $subscribableId)->exists()) {
            abort(404);
        }
    }
}
