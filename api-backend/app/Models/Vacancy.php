<?php

namespace App\Models;

use App\Filters\FilterRequest;
use App\Models\Employer\Employer;
use App\Models\Employer\VacancyResponse;
use App\Models\Traits\Filterable;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Database\Factories\VacancyFactory;
use ElasticScoutDriverPlus\QueryDsl;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * App\Models\Vacancy
 *
 * @property int $id
 * @property string $name
 * @property int $profession_id
 * @property int|null $salary_min
 * @property int|null $salary_max
 * @property bool $salary_negotiable
 * @property int|null $experience_level
 * @property int|null $employment_type
 * @property int|null $schedule
 * @property bool $is_internship
 * @property array $competitions
 * @property array|null $skills
 * @property string|null $responsibilities
 * @property string|null $requirements
 * @property string|null $conditions
 * @property string|null $description
 * @property int|null $city_id
 * @property string|null $working_address
 * @property bool $is_working_address_visible
 * @property bool $show_chat
 * @property int $employer_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read City|null $city
 * @method static VacancyFactory factory(...$parameters)
 * @method static Builder|Vacancy filter(FilterRequest $filters)
 * @method static Builder|Vacancy newModelQuery()
 * @method static Builder|Vacancy newQuery()
 * @method static Builder|Vacancy query()
 * @method static Builder|Vacancy whereCityId($value)
 * @method static Builder|Vacancy whereCompetitions($value)
 * @method static Builder|Vacancy whereConditions($value)
 * @method static Builder|Vacancy whereCreatedAt($value)
 * @method static Builder|Vacancy whereDescription($value)
 * @method static Builder|Vacancy whereEmployerId($value)
 * @method static Builder|Vacancy whereEmploymentType($value)
 * @method static Builder|Vacancy whereExperienceLevel($value)
 * @method static Builder|Vacancy whereId($value)
 * @method static Builder|Vacancy whereIsInternship($value)
 * @method static Builder|Vacancy whereIsWorkingAddressVisible($value)
 * @method static Builder|Vacancy whereName($value)
 * @method static Builder|Vacancy whereProfessionId($value)
 * @method static Builder|Vacancy whereRequirements($value)
 * @method static Builder|Vacancy whereResponsibilities($value)
 * @method static Builder|Vacancy whereSalaryMax($value)
 * @method static Builder|Vacancy whereSalaryMin($value)
 * @method static Builder|Vacancy whereSalaryNegotiable($value)
 * @method static Builder|Vacancy whereSchedule($value)
 * @method static Builder|Vacancy whereShowChat($value)
 * @method static Builder|Vacancy whereSkills($value)
 * @method static Builder|Vacancy whereUpdatedAt($value)
 * @method static Builder|Vacancy whereWorkingAddress($value)
 * @mixin Eloquent
 */
class Vacancy extends Model implements Viewable
{
    use HasFactory;
    use Filterable;
    use Favoriteable;
    use InteractsWithViews;
    use Searchable;
    use QueryDsl;

    protected $guarded = ['id'];

    protected $casts = [
        'competitions' => 'array',
        'skills' => 'array',
        'is_internship' => 'boolean',
        'coords' => 'array',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(VacancyResponse::class);
    }

    public function isUserApplied(int $userId): bool
    {
        return $this->responses()->where('user_id', $userId)->exists();
    }

    public function searchableAs(): string
    {
        return 'vacancies_index';
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'competitions' => implode(' ', $this->competitions ?? []),
            'skills' => implode(' ', $this->skills ?? []),
            'responsibilities' => $this->responsibilities,
            'requirements' => $this->requirements,
            'conditions' => $this->conditions,
            'description' => $this->description,
            'schedule' => optional(Dictionary::getById($this->schedule))->value,
            'profession' => optional($this->profession)->name,
            'experience_level' => optional(Dictionary::getById($this->experience_level))->value,
            'employment_type' => optional(Dictionary::getById($this->employment_type))->value,
            'city' => optional($this->city)->name,
            'working_address' => $this->working_address,
            'employer' => optional($this->employer)->name,
        ];
    }
}
