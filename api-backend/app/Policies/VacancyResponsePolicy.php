<?php

namespace App\Policies;

use App\Models\Employer\Employer;
use App\Models\Employer\VacancyResponse;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacancyResponsePolicy
{
    use HandlesAuthorization;

    public function hideFromUser(User $user, VacancyResponse $vacancyResponse): bool
    {
        return $vacancyResponse->user_id === $user->id;
    }

    public function changeStatus(User $user, VacancyResponse $vacancyResponse)
    {
        return $user->id === $vacancyResponse->vacancy->employer->user->id;
    }
}
