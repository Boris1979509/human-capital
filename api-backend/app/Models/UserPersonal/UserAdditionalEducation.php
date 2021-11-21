<?php

namespace App\Models\UserPersonal;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserPersonal\UserAdditionalEducation
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $organization
 * @property int $year_end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserAdditionalEducation newModelQuery()
 * @method static Builder|UserAdditionalEducation newQuery()
 * @method static Builder|UserAdditionalEducation query()
 * @method static Builder|UserAdditionalEducation whereCreatedAt($value)
 * @method static Builder|UserAdditionalEducation whereId($value)
 * @method static Builder|UserAdditionalEducation whereName($value)
 * @method static Builder|UserAdditionalEducation whereOrganization($value)
 * @method static Builder|UserAdditionalEducation whereUpdatedAt($value)
 * @method static Builder|UserAdditionalEducation whereUserId($value)
 * @method static Builder|UserAdditionalEducation whereYearEnd($value)
 * @mixin Eloquent
 */
class UserAdditionalEducation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
