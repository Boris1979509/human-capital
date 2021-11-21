<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use App\Models\CalendarEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CalendarEntriesController extends Controller
{
    public function show(string $calendareableType, int $calendareableId): CalendarEventResource
    {
        $calendarEntry = CalendarEntry::where([
            'calendareable_type' => $calendareableType,
            'calendareable_id' => $calendareableId,
            'user_id' => auth()->id(),
        ])->firstOrFail();

        return new CalendarEventResource($calendarEntry);
    }

    public function create(string $calendareableType, int $calendareableId): JsonResponse
    {
        CalendarEntry::updateOrCreate([
            'calendareable_type' => $calendareableType,
            'calendareable_id' => $calendareableId,
            'user_id' => auth()->id(),
        ]);

        return response()->json(null, Response::HTTP_CREATED);
    }

    public function delete(string $calendareableType, int $calendareableId): JsonResponse
    {
        CalendarEntry::where([
            'calendareable_type' => $calendareableType,
            'calendareable_id' => $calendareableId,
            'user_id' => auth()->id(),
        ])->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
