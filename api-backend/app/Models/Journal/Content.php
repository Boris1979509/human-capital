<?php

namespace App\Models\Journal;

use App\Http\Requests\SortRequest;
use App\Models\CalendarEntry;
use App\Models\Comment;
use App\Models\Dictionary;
use App\Models\EventRegistration;
use App\Models\Institution\Institution;
use App\Models\Traits\CalendarEntrybility;
use App\Models\Traits\CanBePublished;
use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use App\Models\User;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Database\Factories\Journal\ContentFactory;
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
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Journal\Content
 *
 * @property int $id
 * @property int $type
 * @property int|null $institution_id
 * @property string $title
 * @property string $slug
 * @property string|null $reading_time
 * @property string $text
 * @property bool $comments_enabled
 * @property bool $is_published
 * @property string|null $phone
 * @property string|null $address
 * @property array|null $tags
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $date_start
 * @property Carbon|null $date_end
 * @property Carbon|null $published_at
 * @property int|null $user_id
 * @property-read CalendarEntry|null $calendarEntry
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read Collection|Favorite[] $favorites
 * @property-read int $favorites_count
 * @property-read Institution|null $institution
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read Collection|Dictionary[] $participantsAge
 * @property-read int|null $participants_age_count
 * @property-read Collection|EventSpeaker[] $speakers
 * @property-read int|null $speakers_count
 * @property-read Collection|Dictionary[] $targetAudience
 * @property-read int|null $target_audience_count
 * @method static ContentFactory factory(...$parameters)
 * @method static Builder|Content filter(\App\Filters\FilterRequest $filters)
 * @method static Builder|Content newModelQuery()
 * @method static Builder|Content newQuery()
 * @method static Builder|Content published()
 * @method static Builder|Content query()
 * @method static Builder|Content sort(SortRequest $sorter)
 * @method static Builder|Content whereAddress($value)
 * @method static Builder|Content whereCommentsEnabled($value)
 * @method static Builder|Content whereCreatedAt($value)
 * @method static Builder|Content whereDateEnd($value)
 * @method static Builder|Content whereDateStart($value)
 * @method static Builder|Content whereId($value)
 * @method static Builder|Content whereInstitutionId($value)
 * @method static Builder|Content whereIsPublished($value)
 * @method static Builder|Content wherePhone($value)
 * @method static Builder|Content wherePublishedAt($value)
 * @method static Builder|Content whereReadingTime($value)
 * @method static Builder|Content whereSlug($value)
 * @method static Builder|Content whereTags($value)
 * @method static Builder|Content whereText($value)
 * @method static Builder|Content whereTitle($value)
 * @method static Builder|Content whereType($value)
 * @method static Builder|Content whereUpdatedAt($value)
 * @method static Builder|Content whereUserId($value)
 * @method static Builder|Content withoutEventsInPast()
 * @mixin Eloquent
 */
class Content extends Model implements HasMedia
{
    use HasFactory;
    use Filterable;
    use InteractsWithMedia;
    use Sortable;
    use CanBePublished;
    use Favoriteable;
    use CalendarEntrybility;
    use Searchable;
    use QueryDsl;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('cover')->singleFile();
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
            ->performOnCollections('images');
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CONTAIN, config('app.thumb_width'), 0)
            ->optimize()
            ->performOnCollections('cover');
    }

    protected $guarded = ['id'];

    protected $casts = [
        'tags' => 'array',
        'comments_enabled' => 'boolean',
        'is_registration_required' => 'boolean',
        'is_registration_auto' => 'boolean',
        'is_registration_reminders_enabled' => 'boolean',
        'is_published' => 'boolean',
        'coords' => 'array',
        'registration_fields' => 'array',
        'registration_questions' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
        'date_start',
        'date_end',
        'registration_available_till'
    ];

    public function speakers(): HasMany
    {
        return $this->hasMany(EventSpeaker::class);
    }

    public function scopeWithoutEventsInPast(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->where('type', ContentType::EVENT)
                ->whereNotNull('date_end')
                ->where('date_end', '<', now());
        }, null, null, 'and not');
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function targetAudience(): BelongsToMany
    {
        return $this->belongsToMany(
            Dictionary::class,
            'content_target_audience',
            'content_id',
            'target_audience_id',
        );
    }

    public function participantsAge(): BelongsToMany
    {
        return $this->belongsToMany(
            Dictionary::class,
            'content_participants_age',
            'content_id',
            'participants_age_id',
        );
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }

    public function processedRegistrations(): HasMany
    {
        return $this->registrations()->where('status', '!=', EventRegistration::STATUS_PENDING);
    }

    public function successfulRegistrations(): HasMany
    {
        return $this->registrations()->where('status', EventRegistration::STATUS_ACCEPTED);
    }

    public function hasNoAvailableSlotsForRegistration(): bool
    {
        return $this->successfulRegistrations()->count() >= $this->available_registration_slots;
    }

    public function isRegistrationPeriodExpired(): bool
    {
        return !is_null($this->registration_available_till) && now()->isAfter($this->registration_available_till);
    }


    public function searchableAs(): string
    {
        return 'contents_index';
    }

    public function shouldBeSearchable(): bool
    {
        return $this->isPublic();
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
            'institution' => optional($this->institution)->full_name.' '.optional($this->institution)->short_name,
            'address' => $this->address,
            'tags' => implode(' ', $this->tags ?? []),
            'published_at' => $this->published_at,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'participants_age' => $this->participantsAge->implode('value', ' '),
            'target_audience' => $this->targetAudience->implode('value', ' '),
            'speakers' => $this->speakers
                ->map(function (EventSpeaker $speaker) {
                    return "$speaker->name $speaker->position";
                })
                ->implode(' ')
        ];
    }
}
