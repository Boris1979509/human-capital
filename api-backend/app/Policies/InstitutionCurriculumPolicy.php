<?php

namespace App\Policies;

use App\Models\Institution\Institution;
use App\Models\InstitutionRoles;
use App\Models\User;
use DB;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstitutionCurriculumPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Institution $institution): bool
    {
        return DB::table('users_institution_roles')
            ->where('user_id', $user->id)
            ->where('institution_id', $institution->id)
            ->where('role', InstitutionRoles::OWNER)
            ->exists();
    }
}
