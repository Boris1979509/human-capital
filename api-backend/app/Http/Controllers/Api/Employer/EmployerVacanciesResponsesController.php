<?php


namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Employer\VacancyDetailedResource;
use App\Http\Resources\Employer\VacancyResponseForManagerResource;
use App\Models\Vacancy;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployerVacanciesResponsesController extends Controller
{
    public function index(Vacancy $vacancy): AnonymousResourceCollection
    {
        $this->authorize('manage', [Vacancy::class, $vacancy]);

        return VacancyResponseForManagerResource::collection($vacancy->responses)
            ->additional([
                'vacancy' => new VacancyDetailedResource($vacancy)
            ]);
    }
}
