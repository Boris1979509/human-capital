<?php

namespace App\Models\Institution;

use datetime;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Institution\InstitutionEmployee
 *
 * @property int $id
 * @property int $institution_id
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $position
 * @property bool $approved
 * @property datetime|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee newQuery()
 * @method static Builder|InstitutionEmployee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereInstitutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstitutionEmployee whereUpdatedAt($value)
 * @method static Builder|InstitutionEmployee withTrashed()
 * @method static Builder|InstitutionEmployee withoutTrashed()
 * @mixin Eloquent
 */
class InstitutionEmployee extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table = 'institution_employees';

    protected $guarded = ['id'];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y/m/d H:i:s',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    /**
     * @param  Media|null  $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CONTAIN, config('app.thumb_width'), 0)
            ->optimize()
            ->performOnCollections('avatar');
    }
}
