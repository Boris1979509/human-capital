<?php

namespace App\Policies;

use App\Models\Employer\Employer;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\HandlesAuthorization;

class VacancyPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Vacancy $vacancy): bool
    {
        return $vacancy->employer->user_id === $user->id;
    }
}
