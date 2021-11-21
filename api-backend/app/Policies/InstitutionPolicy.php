<?php

namespace App\Policies;

use App\Models\Institution\Institution;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstitutionPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Institution $institution): bool
    {
        return $institution->managers()->where('user_id', $user->id)->exists();
    }
}
