<?php

namespace App\Http\Controllers\Api\Employer;

use App\Filters\VacanciesFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Employer\VacancyDetailedPublicResource;
use App\Http\Resources\Employer\VacancyPublicCardResource;
use App\Models\Vacancy;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VacanciesPublicController extends Controller
{
    public function index(VacanciesFilter $filter): AnonymousResourceCollection
    {
        $vacancies = Vacancy::query()->filter($filter)->simplePaginate($this->getPerPage());
        return VacancyPublicCardResource::collection($vacancies);
    }

    public function show(Vacancy $vacancy): VacancyDetailedPublicResource
    {
        views($vacancy)->record();
        return new VacancyDetailedPublicResource($vacancy);
    }

    public function similar(
        VacanciesFilter $filter,
        Vacancy $vacancy
    ): AnonymousResourceCollection {
        //TODO: функционал получения похожих вакансий
        $similarVacancies = Vacancy::filter($filter)->where('id', '!=', $vacancy->id)->simplePaginate();

        return VacancyPublicCardResource::collection($similarVacancies);
    }
}
