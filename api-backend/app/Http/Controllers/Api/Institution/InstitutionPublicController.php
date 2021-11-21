<?php

namespace App\Http\Controllers\Api\Institution;

use App\Filters\InstitutionFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Institution\InstitutionSortRequest;
use App\Http\Resources\Institution\InstitutionDetailedPublicResource;
use App\Http\Resources\Institution\InstitutionEmployeeResource;
use App\Http\Resources\Institution\InstitutionPublicResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionSummary;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class InstitutionPublicController extends Controller
{
    public function summary(): JsonResource
    {
        return new JsonResource(Cache::remember('users', config('app.cache_ttl'), function () {
            return InstitutionSummary::get();
        }));
    }

    public function index(InstitutionFilter $filter, InstitutionSortRequest $sorter): AnonymousResourceCollection
    {
        $institutions = Institution::filter($filter)
            ->sort($sorter)
            ->simplePaginate($this->getPerPage());
        return InstitutionPublicResource::collection($institutions);
    }

    public function show(Institution $institution): InstitutionDetailedPublicResource
    {
        return new InstitutionDetailedPublicResource($institution);
    }

    public function employees(Institution $institution): AnonymousResourceCollection
    {
        return InstitutionEmployeeResource::collection($institution->employees()
            ->where('approved', true)
            ->get());
    }
}
