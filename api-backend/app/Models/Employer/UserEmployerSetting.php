<?php

namespace App\Models\Employer;

use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserEmployer\UserEmployerSetting
 *
 * @property int $id
 * @property int $user_id
 * @property string $key
 * @property string|null $value
 * @property string|null $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Employer $employer
 * @method static Builder|UserEmployerSetting newModelQuery()
 * @method static Builder|UserEmployerSetting newQuery()
 * @method static Builder|UserEmployerSetting query()
 * @method static Builder|UserEmployerSetting whereCreatedAt($value)
 * @method static Builder|UserEmployerSetting whereId($value)
 * @method static Builder|UserEmployerSetting whereKey($value)
 * @method static Builder|UserEmployerSetting whereType($value)
 * @method static Builder|UserEmployerSetting whereUpdatedAt($value)
 * @method static Builder|UserEmployerSetting whereUserId($value)
 * @method static Builder|UserEmployerSetting whereValue($value)
 * @mixin Eloquent
 */
class UserEmployerSetting extends Model
{
    protected $table = 'user_employer_settings';

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
