<?php

namespace App\Models\UserPersonal;

use App\Models\Dictionary;
use App\Models\University;
use App\Models\User;
use datetime;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\UserPersonal\UserEducation
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $university_id
 * @property int|null $edu_degree_id
 * @property int|null $edu_status_id
 * @property int|null $edu_quality_id
 * @property int|null $year_begin
 * @property int|null $year_end
 * @property string|null $specialty
 * @property string|null $document_number
 * @property datetime|null $document_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read Dictionary|null $quality
 * @property-read University|null $university
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation newQuery()
 * @method static Builder|UserEducation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereDocumentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereEduDegreeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereEduQualityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereEduStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereSpecialty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereUniversityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereYearBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEducation whereYearEnd($value)
 * @method static Builder|UserEducation withTrashed()
 * @method static Builder|UserEducation withoutTrashed()
 * @mixin Eloquent
 */
class UserEducation extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'user_education';

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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'document_date' => 'datetime:Y/m/d',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('education');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function quality()
    {
        return $this->belongsTo(Dictionary::class, 'edu_quality_id');
    }
}
