<?php

namespace App\Models\Institution;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Institution\InstitutionSetting
 *
 * @property int $id
 * @property int $institution_id
 * @property int $user_id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $type
 * @property-read \App\Models\Institution\Institution $institution
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting whereInstitutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionSetting whereValue($value)
 * @mixin \Eloquent
 */
class InstitutionSetting extends Model
{
    use HasFactory;
    protected $table = 'institution_settings';

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
