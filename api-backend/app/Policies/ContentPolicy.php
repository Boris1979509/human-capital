<?php

namespace App\Policies;

use App\Models\Institution\Institution;
use App\Models\InstitutionRoles;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use App\Models\User;
use DB;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Content $content): bool
    {
        if ($content->isPublic()) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $this->manage($user, $content);
    }

    public function create(User $user, Institution $institution): bool
    {
        return $this->userIsManagerOfInstitution($user, $institution->id);
    }

    public function createAsEmployer(User $user, int $contentType): bool
    {
        return $user->type === User::TYPE_USER_EMPLOYER && $contentType !== ContentType::ARTICLE;
    }

    public function viewAny(User $user, Institution $institution): bool
    {
        return $this->userIsManagerOfInstitution($user, $institution->id)
            || $user->type === User::TYPE_USER_EMPLOYER;
    }

    public function manage(User $user, Content $content): bool
    {
        return $this->userIsManagerOfInstitution($user, $content->institution_id) || $user->id === $content->user_id;
    }

    private function userIsManagerOfInstitution(User $user, ?int $institutionId): bool
    {
        return DB::table('users_institution_roles')
            ->where('user_id', $user->id)
            ->where('institution_id', $institutionId)
            ->where('role', InstitutionRoles::OWNER)
            ->exists();
    }
}
