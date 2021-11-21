<?php

namespace App\Http\Controllers\Api\Employer;

use App\Filters\EmployerFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Employer\EmployerDetailedPublicResource;
use App\Http\Resources\Employer\EmployerPublicCardResource;
use App\Models\Employer\Employer;
use App\Models\EmployersSummary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployerPublicController extends Controller
{
    public function index(EmployerFilter $filter): AnonymousResourceCollection
    {
        $vacancies = Employer::query()
            ->filter($filter)
            ->withCount('vacancies')
            ->simplePaginate($this->getPerPage());
        return EmployerPublicCardResource::collection($vacancies);
    }

    public function show(Employer $employer): EmployerDetailedPublicResource
    {
        return new EmployerDetailedPublicResource($employer);
    }

    public function summary(EmployersSummary $summary): JsonResponse
    {
        return response()->json($summary->get());
    }
}
