<?php

namespace App\Providers;

use App\Models\Employer\VacancyResponse;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\Journal\Content;
use App\Models\Vacancy;
use App\Policies\ContentPolicy;
use App\Policies\InstitutionCurriculumPolicy;
use App\Policies\InstitutionPolicy;
use App\Policies\VacancyPolicy;
use App\Policies\VacancyResponsePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Content::class => ContentPolicy::class,
        InstitutionCurriculum::class => InstitutionCurriculumPolicy::class,
        Institution::class => InstitutionPolicy::class,
        Vacancy::class => VacancyPolicy::class,
        VacancyResponse::class => VacancyResponsePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
