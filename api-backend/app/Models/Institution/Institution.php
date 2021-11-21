<?php

namespace App\Models\Institution;

use App\Filters\FilterRequest;
use App\Http\Requests\SortRequest;
use App\Models\City;
use App\Models\Dictionary;
use App\Models\Journal\Content;
use App\Models\Rating;
use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use App\Models\University;
use App\Models\User;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Database\Factories\Institution\InstitutionFactory;
use datetime;
use ElasticScoutDriverPlus\QueryDsl;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Institution\Institution
 *
 * @property int $id
 * @property int|null $university_id
 * @property string|null $full_name
 * @property string|null $short_name
 * @property int|null $inst_type_id
 * @property int|null $inst_diploma_id
 * @property string|null $description
 * @property int|null $count_students
 * @property int|null $count_programs
 * @property int|null $avg_score
 * @property int|null $avg_salary
 * @property int|null $rating_students
 * @property float|null $rating_employers
 * @property float|null $rate_employment
 * @property string|null $website
 * @property string|null $link_vk
 * @property string|null $link_fb
 * @property string|null $inn
 * @property string|null $ogrn
 * @property string|null $bank
 * @property string|null $bank_inn
 * @property string|null $account
 * @property string|null $account_corr
 * @property string|null $bik
 * @property string|null $kpp
 * @property datetime|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property array|null $contacts
 * @property int|null $city_id
 * @property string|null $oktmo
 * @property string|null $contact_description
 * @property bool|null $show_rating_students
 * @property bool|null $entrance_test
 * @property string|null $entrance_test_description
 * @property int|null $employment_assistance
 * @property bool|null $show_rating_employers
 * @property int|null $avg_score_ege
 * @property int|null $avg_score_oge
 * @property int|null $percent_enrolled_budget
 * @property int|null $count_directions
 * @property bool|null $rating_show
 * @property-read City|null $city
 * @property-read Collection|Content[] $content
 * @property-read int|null $content_count
 * @property-read Collection|InstitutionCurriculum[] $curricula
 * @property-read int|null $curricula_count
 * @property-read Collection|InstitutionEmployee[] $employees
 * @property-read int|null $employees_count
 * @property-read Collection|Favorite[] $favorites
 * @property-read int $favorites_count
 * @property-read Collection|User[] $managers
 * @property-read int|null $managers_count
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read Collection|Rating[] $ratings
 * @property-read int|null $ratings_count
 * @property-read University|null $university
 * @method static InstitutionFactory factory(...$parameters)
 * @method static Builder|Institution filter(FilterRequest $filters)
 * @method static Builder|Institution newModelQuery()
 * @method static Builder|Institution newQuery()
 * @method static \Illuminate\Database\Query\Builder|Institution onlyTrashed()
 * @method static Builder|Institution query()
 * @method static Builder|Institution sort(SortRequest $sorter)
 * @method static Builder|Institution whereAccount($value)
 * @method static Builder|Institution whereAccountCorr($value)
 * @method static Builder|Institution whereAvgSalary($value)
 * @method static Builder|Institution whereAvgScore($value)
 * @method static Builder|Institution whereAvgScoreEge($value)
 * @method static Builder|Institution whereAvgScoreOge($value)
 * @method static Builder|Institution whereBank($value)
 * @method static Builder|Institution whereBankInn($value)
 * @method static Builder|Institution whereBik($value)
 * @method static Builder|Institution whereCityId($value)
 * @method static Builder|Institution whereContactDescription($value)
 * @method static Builder|Institution whereContacts($value)
 * @method static Builder|Institution whereCountDirections($value)
 * @method static Builder|Institution whereCountPrograms($value)
 * @method static Builder|Institution whereCountStudents($value)
 * @method static Builder|Institution whereCreatedAt($value)
 * @method static Builder|Institution whereDeletedAt($value)
 * @method static Builder|Institution whereDescription($value)
 * @method static Builder|Institution whereEmploymentAssistance($value)
 * @method static Builder|Institution whereEntranceTest($value)
 * @method static Builder|Institution whereEntranceTestDescription($value)
 * @method static Builder|Institution whereFullName($value)
 * @method static Builder|Institution whereId($value)
 * @method static Builder|Institution whereInn($value)
 * @method static Builder|Institution whereInstDiplomaId($value)
 * @method static Builder|Institution whereInstTypeId($value)
 * @method static Builder|Institution whereKpp($value)
 * @method static Builder|Institution whereLinkFb($value)
 * @method static Builder|Institution whereLinkVk($value)
 * @method static Builder|Institution whereOgrn($value)
 * @method static Builder|Institution whereOktmo($value)
 * @method static Builder|Institution wherePercentEnrolledBudget($value)
 * @method static Builder|Institution whereRateEmployment($value)
 * @method static Builder|Institution whereRatingEmployers($value)
 * @method static Builder|Institution whereRatingShow($value)
 * @method static Builder|Institution whereRatingStudents($value)
 * @method static Builder|Institution whereShortName($value)
 * @method static Builder|Institution whereShowRatingEmployers($value)
 * @method static Builder|Institution whereShowRatingStudents($value)
 * @method static Builder|Institution whereUniversityId($value)
 * @method static Builder|Institution whereUpdatedAt($value)
 * @method static Builder|Institution whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|Institution withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Institution withoutTrashed()
 * @mixin Eloquent
 */
class Institution extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use Filterable;
    use Sortable;
    use Favoriteable;
    use Searchable;
    use QueryDsl;

    protected $guarded = ['id'];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'contacts' => 'array',
        'created_at' => 'datetime:Y/m/d H:i:s',
        'employment_assistance' => 'integer',
    ];

    public const PROGRESS_FIELDS = [
        'full_name',
        'short_name',
        'inst_type_id',
        'inst_diploma_id',
        'description',
        'count_students',
        'website',
        'link_vk',
        'link_fb',
        'inn',
        'bank',
        'account',
        'account_corr',
        'kpp',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Dictionary::class, 'inst_type_id');
    }

    public function diploma(): BelongsTo
    {
        return $this->belongsTo(Dictionary::class, 'inst_diploma_id');
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

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function curricula(): HasMany
    {
        return $this->hasMany(InstitutionCurriculum::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(InstitutionEmployee::class);
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_institution_roles');
    }

    public function content(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(InstitutionSetting::class)->where('user_id', '=', Auth::user()->id);
    }

    public function getProgress(): int
    {
        $progress = 0;
        foreach (self::PROGRESS_FIELDS as $field) {
            $progress += $this->$field ? 5 : 0;
        }

        $progress += $this->curricula->count() ? 20 : 0;
        $progress += $this->employees->count() ? 10 : 0;

        return $progress;
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }


    public function searchableAs(): string
    {
        return 'curricula_index';
    }

    public function toSearchableArray(): array
    {
        return [
            'full_name' => $this->full_name,
            'short_name' => $this->short_name,
            'description' => $this->description,
            'type' => optional($this->type)->value,
            'diploma' => optional($this->diploma)->value,
            'website' => $this->website,
            'city' => optional($this->city)->name,
            'contact_description' => $this->contact_description,
            'entrance_test_description' => $this->entrance_test_description,
            'employees' => $this->employees
                ->map(function (InstitutionEmployee $employee) {
                    return "$employee->first_name $employee->last_name $employee->middle_name";
                })
                ->implode(' '),
        ];
    }
}
