<?php

namespace App\Models\UserPersonal;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UserPersonal\UserJob
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $company
 * @property string|null $website
 * @property string|null $position
 * @property string|null $description
 * @property int|null $year_begin
 * @property int|null $year_end
 * @property int|null $month_begin
 * @property int|null $month_end
 * @property bool|null $until_now
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob newQuery()
 * @method static \Illuminate\Database\Query\Builder|UserJob onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereMonthBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereMonthEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereUntilNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereYearBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserJob whereYearEnd($value)
 * @method static \Illuminate\Database\Query\Builder|UserJob withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserJob withoutTrashed()
 * @mixin \Eloquent
 */
class UserJob extends Model
{
    use SoftDeletes;

    protected $table = 'user_jobs';

    /**
     * Атрибуты, для которых запрещено массовое назначение.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
