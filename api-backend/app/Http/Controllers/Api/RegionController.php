<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RegionSummary;
use Cache;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'name' => config('app.region')
        ]);
    }

    public function summary(RegionSummary $summary): JsonResponse
    {
        return Cache::remember('region.summary', config('app.cache_ttl'), function () use ($summary) {
            return response()->json($summary->get());
        });
    }
}
