<?php

namespace App\Models\UserPersonal;

use App\Models\City;
use App\Models\User;
use datetime;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserPersonal\UserPersonal
 *
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property int|null $sex
 * @property Carbon|null $birthday
 * @property int|null $nationality_id
 * @property int|null $city_id
 * @property int|null $country_id
 * @property int|null $document_id
 * @property string|null $document_series
 * @property string|null $document_number
 * @property datetime|null $document_date
 * @property string|null $inn
 * @property string|null $snills
 * @property string|null $link_vk
 * @property string|null $link_fb
 * @property string|null $description
 * @property array|null $skills
 * @property array|null $qualities
 * @property-read City|null $city
 * @property-read User $user
 * @method static Builder|UserPersonal newModelQuery()
 * @method static Builder|UserPersonal newQuery()
 * @method static Builder|UserPersonal query()
 * @method static Builder|UserPersonal whereBirthday($value)
 * @method static Builder|UserPersonal whereCityId($value)
 * @method static Builder|UserPersonal whereCountryId($value)
 * @method static Builder|UserPersonal whereDescription($value)
 * @method static Builder|UserPersonal whereDocumentDate($value)
 * @method static Builder|UserPersonal whereDocumentId($value)
 * @method static Builder|UserPersonal whereDocumentNumber($value)
 * @method static Builder|UserPersonal whereDocumentSeries($value)
 * @method static Builder|UserPersonal whereFirstName($value)
 * @method static Builder|UserPersonal whereInn($value)
 * @method static Builder|UserPersonal whereLastName($value)
 * @method static Builder|UserPersonal whereLinkFb($value)
 * @method static Builder|UserPersonal whereLinkVk($value)
 * @method static Builder|UserPersonal whereMiddleName($value)
 * @method static Builder|UserPersonal whereNationalityId($value)
 * @method static Builder|UserPersonal whereQualities($value)
 * @method static Builder|UserPersonal whereSex($value)
 * @method static Builder|UserPersonal whereSkills($value)
 * @method static Builder|UserPersonal whereSnills($value)
 * @method static Builder|UserPersonal whereUserId($value)
 * @mixin Eloquent
 */
class UserPersonal extends Model
{
    protected $table = 'user_personals';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = ['user_id'];
    protected $hidden = ['user_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'skills' => 'array',
        'qualities' => 'array',
        'document_date' => 'datetime:Y/m/d',
    ];

    protected $dates = [
        'birthday'
    ];

    public const PROGRESS_FIELDS = [
        'first_name',
        'last_name',
        'middle_name',
        'city_id',
        'sex',
        'birthday',
        'country_id',
        'link_vk',
        'link_fb',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

//    public function getSkillsAttribute($value): ?string
//    {
//        return !empty($value) ? implode(',', json_decode($value)) : null;
//    }
//
//    public function getQualitiesAttribute($value): ?string
//    {
//        return !empty($value) ? implode(',', json_decode($value)) : null;
//    }
}
