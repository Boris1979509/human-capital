<?php

namespace App\Models\Institution;

use App\Filters\FilterRequest;
use App\Http\Requests\SortRequest;
use App\Models\CalendarEntry;
use App\Models\Dictionary;
use App\Models\Traits\CalendarEntrybility;
use App\Models\Traits\CanBePublished;
use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Database\Factories\Institution\CurriculumFactory;
use ElasticScoutDriverPlus\QueryDsl;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Institution\InstitutionCurriculum
 *
 * @property int $id
 * @property string $name
 * @property string|null $direction_of_study
 * @property int|null $type_id
 * @property string|null $description
 * @property string|null $budget_places
 * @property int|null $passing_score
 * @property string|null $duration
 * @property bool $is_admission_exam
 * @property bool $is_admission_olympiad
 * @property string|null $admission_olympiad_conditions
 * @property bool $is_admission_additional_exam
 * @property string|null $admission_additional_exam_conditions
 * @property bool $reviews_enabled
 * @property bool $questions_enabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $institution_id
 * @property array|null $learning_options
 * @property array|null $admission_exams
 * @property array|null $result_professions
 * @property array|null $competitions
 * @property bool $is_published
 * @property Carbon|null $published_at
 * @property array|null $worth
 * @property array|null $result_skills
 * @property-read CalendarEntry|null $calendarEntry
 * @property-read Dictionary|null $curriculumType
 * @property-read Collection|Dictionary[] $directions
 * @property-read int|null $directions_count
 * @property-read Collection|Favorite[] $favorites
 * @property-read int $favorites_count
 * @property-read Institution $institution
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static CurriculumFactory factory(...$parameters)
 * @method static Builder|InstitutionCurriculum filter(FilterRequest $filters)
 * @method static Builder|InstitutionCurriculum newModelQuery()
 * @method static Builder|InstitutionCurriculum newQuery()
 * @method static Builder|InstitutionCurriculum published()
 * @method static Builder|InstitutionCurriculum query()
 * @method static Builder|InstitutionCurriculum sort(SortRequest $sorter)
 * @method static Builder|InstitutionCurriculum whereAdmissionAdditionalExamConditions($value)
 * @method static Builder|InstitutionCurriculum whereAdmissionExams($value)
 * @method static Builder|InstitutionCurriculum whereAdmissionOlympiadConditions($value)
 * @method static Builder|InstitutionCurriculum whereBudgetPlaces($value)
 * @method static Builder|InstitutionCurriculum whereCompetitions($value)
 * @method static Builder|InstitutionCurriculum whereCreatedAt($value)
 * @method static Builder|InstitutionCurriculum whereDescription($value)
 * @method static Builder|InstitutionCurriculum whereDirectionOfStudy($value)
 * @method static Builder|InstitutionCurriculum whereDuration($value)
 * @method static Builder|InstitutionCurriculum whereId($value)
 * @method static Builder|InstitutionCurriculum whereInstitutionId($value)
 * @method static Builder|InstitutionCurriculum whereIsAdmissionAdditionalExam($value)
 * @method static Builder|InstitutionCurriculum whereIsAdmissionExam($value)
 * @method static Builder|InstitutionCurriculum whereIsAdmissionOlympiad($value)
 * @method static Builder|InstitutionCurriculum whereIsPublished($value)
 * @method static Builder|InstitutionCurriculum whereLearningOptions($value)
 * @method static Builder|InstitutionCurriculum whereName($value)
 * @method static Builder|InstitutionCurriculum wherePassingScore($value)
 * @method static Builder|InstitutionCurriculum wherePublishedAt($value)
 * @method static Builder|InstitutionCurriculum whereQuestionsEnabled($value)
 * @method static Builder|InstitutionCurriculum whereResultProfessions($value)
 * @method static Builder|InstitutionCurriculum whereResultSkills($value)
 * @method static Builder|InstitutionCurriculum whereReviewsEnabled($value)
 * @method static Builder|InstitutionCurriculum whereTypeId($value)
 * @method static Builder|InstitutionCurriculum whereUpdatedAt($value)
 * @method static Builder|InstitutionCurriculum whereWorth($value)
 * @mixin Eloquent
 */
class InstitutionCurriculum extends Model implements HasMedia
{
    use HasFactory;
    use Filterable;
    use Sortable;
    use CanBePublished;
    use Favoriteable;
    use InteractsWithMedia;
    use CalendarEntrybility;
    use Searchable;
    use QueryDsl;

    protected static function newFactory(): CurriculumFactory
    {
        return CurriculumFactory::new();
    }

    protected $guarded = ['id', 'university_id'];

    protected $casts = [
        'admission_exams' => 'array',
        'competitions' => 'array',
        'learning_options' => 'array',
        'result_professions' => 'array',
        'worth' => 'array',
        'result_skills' => 'array',
        'is_published' => 'boolean',
        'is_admission_exam' => 'boolean',
        'is_admission_olympiad' => 'boolean',
        'is_admission_additional_exam' => 'boolean',
        'reviews_enabled' => 'boolean',
        'questions_enabled' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function curriculumType(): BelongsTo
    {
        return $this->belongsTo(Dictionary::class, 'type_id');
    }

    public function directions(): BelongsToMany
    {
        return $this->belongsToMany(
            Dictionary::class,
            'institution_curricula_direction',
            'curriculum_id',
            'direction_id',
        );
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        unset($array['competitions']);
        return $array;
    }

    public function getMinPrice(): string
    {
        $minPrice = collect($this->learning_options)->min('cost');
        return "от $minPrice ₽";
    }

    public function searchableAs(): string
    {
        return 'curricula_index';
    }

    public function shouldBeSearchable(): bool
    {
        return $this->isPublic();
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'direction_of_study' => $this->directions->implode('value', ' '),
            'admission_olympiad_conditions' => $this->admission_olympiad_conditions,
            'admission_additional_exam_conditions' => $this->admission_additional_exam_conditions,
            'institution' => $this->institution->short_name.' '.$this->institution->full_name,
            'learning_options' => collect($this->learning_options)
                ->map(function (array $option) {
                    $auditory = Dictionary::getById($option['auditory']);
                    $eduForm = Dictionary::getById($option['edu_form']);
                    return "$auditory $eduForm";
                })
                ->implode(' '),
            'admission_exams' => collect($this->admission_exams)
                ->map(function (array $option) {
                    return Dictionary::getById($option['subject']);
                })
                ->implode(' '),
            'result_professions' => implode(' ', $this->result_professions ?? []),
            'competitions' => implode(' ', $this->competitions ?? []),
            'result_skills' => implode(' ', $this->result_skills ?? []),
            'published_at' => $this->published_at,
        ];
    }
}
