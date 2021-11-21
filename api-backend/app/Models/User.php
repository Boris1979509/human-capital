<?php

namespace App\Models;

use App\Events\UserRegistered;
use App\Models\Employer\VacancyResponse;
use App\Models\Institution\Institution;
use App\Models\Traits\CalendarEntryability;
use App\Models\Employer\Employer;
use App\Models\User\SocialAccount;
use App\Models\UserPersonal\UserAdditionalEducation;
use App\Models\UserPersonal\UserEducation;
use App\Models\UserPersonal\UserJob;
use App\Models\UserPersonal\UserPersonal;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;
use Database\Factories\UserFactory;
use datetime;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\User
 *
 * @property int $id
 * @property int $type
 * @property string|null $phone
 * @property string $email
 * @property datetime|null $email_verified_at
 * @property datetime|null $terms_at
 * @property string $password
 * @property string|null $api_token
 * @property string|null $remember_token
 * @property datetime|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|UserAdditionalEducation[] $additionalEducation
 * @property-read int|null $additional_education_count
 * @property-read Collection|CalendarEntry[] $calendarEntries
 * @property-read int|null $calendar_entries_count
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|UserEducation[] $education
 * @property-read int|null $education_count
 * @property-read Employer|null $employer
 * @property-read Collection|Favorite[] $favorites
 * @property-read int|null $favorites_count
 * @property-read Collection|UserJob[] $jobs
 * @property-read int|null $jobs_count
 * @property-read Collection|Institution[] $managedInstitutions
 * @property-read int|null $managed_institutions_count
 * @property-read MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read UserPersonal|null $personal
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTermsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static Builder|User withTrashed()
 * @method static Builder|User withoutTrashed()
 * @mixin Eloquent
 */
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable, SoftDeletes, HasFactory, InteractsWithMedia, Favoriteability;

    public const TYPE_USER_PERSONAL = 1;
    public const TYPE_USER_INSTITUTION = 2;
    public const TYPE_USER_EMPLOYER = 3;
    public const TYPE_USER_EXPERT = 4;

    public const TYPES_USER = [
        self::TYPE_USER_PERSONAL => 'Физ. лицо',
        self::TYPE_USER_INSTITUTION => 'Образовательное учреждение',
        self::TYPE_USER_EMPLOYER => 'Работодатель',
        self::TYPE_USER_EXPERT => 'Эксперт',
    ];

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'updated_at',
        'deleted_at',
        'media'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'terms_at' => 'datetime:Y/m/d H:i:s',
        'created_at' => 'datetime:Y/m/d H:i:s',
        'email_verified_at' => 'datetime:Y/m/d H:i:s',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('job');
        $this->addMediaCollection('images');
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

    /**
     * @return HasOne
     */
    public function personal(): HasOne
    {
        return $this->hasOne(UserPersonal::class);
    }

    /**
     * @return HasOne
     */
    public function employer(): HasOne
    {
        return $this->hasOne(Employer::class);
    }

    /**
     * @return HasMany
     */
    public function education(): HasMany
    {
        return $this->hasMany(UserEducation::class);
    }

    /**
     * @return HasMany
     */
    public function additionalEducation(): HasMany
    {
        return $this->hasMany(UserAdditionalEducation::class);
    }

    /**
     * @return HasMany
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(UserJob::class);
    }

    public function managedInstitutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'users_institution_roles');
    }

    /**
     * @return HasMany
     */
    public function calendarEntries(): HasMany
    {
        return $this->hasMany(CalendarEntry::class, 'user_id');
    }

    public function getProgress(): int
    {
        $progress = $this->email ? 5 : 0;
        $progress += $this->phone ? 5 : 0;

        foreach (UserPersonal::PROGRESS_FIELDS as $field) {
            $progress += optional($this->personal)->$field ? 5 : 0;
        }

        $progress += $this->jobs->count() ? 20 : 0;
        $progress += $this->education->count() ? 20 : 0;

        return $progress;
    }

    public function responses(): HasMany
    {
        return $this->hasMany(VacancyResponse::class);
    }


    protected static function boot()
    {
        parent::boot();
        self::creating(function (self $model) {
            $model->api_token = $model->api_token ?: Str::random(80);
        });
        self::created(function (self $model) {
            event(new UserRegistered($model));
        });
    }

    /**
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     * @param  string  $providerName
     *
     * @return User
     */
    public static function createBySocialProvider(
        \Laravel\Socialite\Contracts\User $providerUser,
        string $providerName
    ): User {
        $email = $providerUser->getEmail();

        /** @var User $user */
        $user = self::create([
            'email' => $email,
            'password' => '-'
        ]);
        $user->personal()->create([
            'first_name' => $providerUser->getName(),
        ]);

        return $user;
    }

    /**
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     * @return User
     */
    public function updateBySocialProvider(\Laravel\Socialite\Contracts\User $providerUser)
    {
        /** @var User $user */
        $this->update([
            'email' => $providerUser->getEmail(),
        ]);
        $userPersonal = $this->personal;
        if ($userPersonal) {
            $this->personal->update([
                'first_name' => $providerUser->getName()
            ]);
        } else {
            $this->personal()->create(['first_name' => $providerUser->getName()]);
        }

        return $this;
    }

    /**
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     */
    public function getOrganizationsBySocialProvider(\Laravel\Socialite\Contracts\User $providerUser)
    {
        $providerUserOrganizations = collect($providerUser->getRaw())->get('organizations') ?? [];

        foreach ($providerUserOrganizations as $organization) {
            if ($organization["role"] !== 'DIRECTOR') {
                return;
            }

            $organizationArray = [
                'title' => $organization["fullName"] ?? null,
                'description' => $organization["description"] ?? null,
                'phone' => optional(collect($organization["contacts"])->where('type', 'TEL')->first())["address"],
                'email' => optional(collect($organization["contacts"])->where('type', 'EMAIL')->first())["address"],
                'address' => optional(collect($organization["addresses"])->where('type', 'OPS')->first())["addressStr"],
                'website' => $organization["website"] ?? null,
                'logo' => $organization["logo"] ?? null,
                'type' => (int) $organization["main_okved"]["code"] === 79 ? AgencyType::TRAVEL : AgencyType::ORG
            ];

            $organizationLegalArray = [
                'legal_information' => [
                    'fullName' => $organization["fullName"] ?? null,
                    'shortName' => $organization["shortName"] ?? null,
                    'inn' => $organization["inn"] ?? null,
                    'kpp' => $organization["kpp"] ?? null,
                    'ogrn' => $organization["ogrn"] ?? null,
                    'email' => optional(collect($organization["contacts"])->where('type', 'EMAIL')->first())["address"],
                    'address' => optional(collect($organization["addresses"])->where('type',
                        'OLG')->first())["addressStr"],
                    'addresses' => $organization['addresses'],
                    'ceo' => $organization["ceo"] ?? null,
                    'account' => $organization["account"] ?? null,
                    'main_okved' => implode(" ", $organization["main_okved"]),
                    'registrationDate' => $organization["registrationDate"] ?? null
                ]
            ];

            switch (mb_strtolower($organization["legalStatus"])) {
                case "legal":
                    $organizationLegalArray['legal_information']['legal_status'] = "Юридическое лицо";
                    break;
                case "individual":
                    $organizationLegalArray['legal_information']['legal_status'] = "Индивидуальный предприниматель";
                    break;
                default:
                    break;
            }

            /**
             * Organizations storing in agencies table
             */
            if ($agency = Agency::withTrashed()->whereUserId($this->id)->whereProviderId($organization["id"])->first()) {
                $agency->update($organizationLegalArray);
            } else {
                $organizationArray += [
                    'status' => 'new',
                    'provider_id' => $organization["id"]
                ];
                $this->agencies()->create($organizationArray + $organizationLegalArray);
            }
        }
    }

    public function keycloak(): HasOne
    {
        return $this->hasOne(SocialAccount::class)->where(['provider' => 'keycloak']);
    }

    public function uid()
    {
        return $this->keycloak ? $this->keycloak->provider_user_id : null;
    }

    public function regenerateToken()
    {
        $this->api_token = Str::random(80);
        $this->save();
    }

    public function toArray()
    {
        return parent::toArray() + ['provider_user_id' => $this->uid()];
    }
}
