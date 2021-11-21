<?php

namespace App\Models\Employer;

use App\Models\City;
use App\Models\Dictionary;
use App\Models\Rating;
use App\Models\Subscription;
use App\Models\Traits\Filterable;
use App\Models\User;
use App\Models\Vacancy;
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use ElasticScoutDriverPlus\QueryDsl;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\UserEmployer\UserEmployer
 *
 * @property int $user_id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $branch_id
 * @property int|null $count_employees
 * @property bool $is_internship
 * @property int|null $city_id
 * @property string|null $address
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $website
 * @property bool $show_contacts
 * @property array|null $contacts
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read Collection|UserEmployerSetting[] $settings
 * @property-read int|null $settings_count
 * @property-read User $user
 * @method static Builder|Employer newModelQuery()
 * @method static Builder|Employer newQuery()
 * @method static Builder|Employer query()
 * @method static Builder|Employer whereAddress($value)
 * @method static Builder|Employer whereBranchId($value)
 * @method static Builder|Employer whereCityId($value)
 * @method static Builder|Employer whereContacts($value)
 * @method static Builder|Employer whereCountEmployees($value)
 * @method static Builder|Employer whereCreatedAt($value)
 * @method static Builder|Employer whereDescription($value)
 * @method static Builder|Employer whereIsInternship($value)
 * @method static Builder|Employer whereLatitude($value)
 * @method static Builder|Employer whereLongitude($value)
 * @method static Builder|Employer whereName($value)
 * @method static Builder|Employer whereShowContacts($value)
 * @method static Builder|Employer whereUpdatedAt($value)
 * @method static Builder|Employer whereUserId($value)
 * @method static Builder|Employer whereWebsite($value)
 * @mixin Eloquent
 */
class Employer extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use Favoriteable;
    use Filterable;
    use Searchable;
    use QueryDsl;

    protected $table = 'employers';

    protected $guarded = ['id', 'user_id'];
    protected $hidden = ['id', 'user_id'];
    protected $casts = [
        'contacts' => 'array',
        'coords' => 'array'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('images');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(UserEmployerSetting::class, 'user_id', 'user_id');
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(Vacancy::class);
    }

    public function getInternshipsCount(): int
    {
        return $this->vacancies()->where('is_internship', true)->count();
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function subscriptions(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function searchableAs(): string
    {
        return 'employers_index';
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'branch' => optional(Dictionary::getById($this->branch_id))->value,
            'city' => optional($this->city)->name,
            'address' => $this->address,
            'website' => $this->website,
        ];
    }
}
