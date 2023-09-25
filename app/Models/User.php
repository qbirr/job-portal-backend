<?php

namespace App\Models;

use App\Notifications\PasswordReset;
use App\Notifications\UserVerifyNotification;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $phone
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $dob
 * @property int|null $gender
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property int $is_active
 * @property int $is_verified
 * @property int|null $owner_id
 * @property string|null $owner_type
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Candidate|null $candidate
 * @property-read Collection|Skill[] $candidateSkill
 * @property-read int|null $candidate_skill_count
 * @property-read mixed $avatar
 * @property-read string $full_name
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[]
 *     $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereCity($value)
 * @method static Builder|User whereCountry($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDob($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereGender($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsActive($value)
 * @method static Builder|User whereIsVerified($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User whereOwnerId($value)
 * @method static Builder|User whereOwnerType($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereState($value)
 * @property-read Company|null $company
 * @method static Builder|User whereUpdatedAt($value)
 * @property string $language
 * @property-read Collection|Language[] $candidateLanguage
 * @property-read int|null $candidate_language_count
 * @method static Builder|User whereLanguage($value)
 * @property int|null $country_id
 * @property int|null $state_id
 * @property int|null $city_id
 * @property int $profile_views
 * @property-read Collection|FavouriteCompany[] $followings
 * @property-read int|null $followings_count
 * @method static Builder|User whereCityId($value)
 * @method static Builder|User whereCountryId($value)
 * @method static Builder|User whereProfileViews($value)
 * @method static Builder|User whereStateId($value)
 * @property-read mixed $city_name
 * @property-read mixed $country_name
 * @property-read mixed $state_name
 * @property string|null $facebook_url
 * @property string|null $twitter_url
 * @property string|null $linkedin_url
 * @property string|null $google_plus_url
 * @property string|null $pinterest_url
 * @property int $is_default
 * @method static Builder|User whereFacebookUrl($value)
 * @method static Builder|User whereGooglePlusUrl($value)
 * @method static Builder|User whereIsDefault($value)
 * @method static Builder|User whereLinkedinUrl($value)
 * @method static Builder|User wherePinterestUrl($value)
 * @property string|null $stripe_id
 * @property-read Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static Builder|User whereStripeId($value)
 * @method static Builder|User whereTwitterUrl($value)
 * @property string|null $region_code
 * @property-read bool $is_online_profile_availbal
 * @method static Builder|User whereRegionCode($value)
 * @property string|null $theme_mode
 * @method static Builder|User whereThemeMode($value)
 * @mixin Eloquent
 */
class User extends Authenticatable implements HasMedia {
    use HasApiTokens, Notifiable, HasRoles, InteractsWithMedia, Billable;

    const DARK_MODE = 1;

    const LIGHT_MODE = 0;

    const PROFILE = 'profile-pictures';

    const ACTIVE = 1;

    const LANGUAGES = [
        'ar' => 'Arabic',
        'zh' => 'Chinese',
        'en' => 'English',
        'fr' => 'French',
        'de' => 'German',
        'pt' => 'Portuguese',
        'ru' => 'Russian',
        'es' => 'Spanish',
        'tr' => 'Turkish',
    ];

    const LANGUAGES_IMAGE = [
        'en' => 'assets/img/united-states.svg',
        'es' => 'assets/img/spain.svg',
        'fr' => 'assets/img/france.svg',
        'de' => 'assets/img/germany.svg',
        'ru' => 'assets/img/russia.svg',
        'pt' => 'assets/img/portugal.svg',
        'ar' => 'assets/img/iraq.svg',
        'zh' => 'assets/img/china.svg',
        'tr' => 'assets/img/turkey.svg',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'dob',
        'gender',
        'country_id',
        'state_id',
        'city_id',
        'is_active',
        'is_verified',
        'phone',
        'email_verified_at',
        'owner_id',
        'owner_type',
        'language',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'google_plus_url',
        'pinterest_url',
        'is_default',
        'region_code',
    ];

    /**
     * @var array
     */
    protected $appends = ['full_name', 'avatar', 'country_name', 'state_name', 'city_name'];

    protected $with = ['media', 'country', 'city', 'state'];

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function getCountryNameAttribute(): ?string {
        if (!empty($this->country)) {
            return $this->country->name;
        }
        return null;
    }

    public function getStateNameAttribute(): ?string {
        if (!empty($this->state)) {
            return $this->state->name;
        }
        return null;
    }

    public function getCityNameAttribute(): ?string {
        if (!empty($this->city)) {
            return $this->city->name;
        }
        return null;
    }

    /**
     * @return string
     */
    public function getAvatarAttribute(): string {
        /** @var Media $media */
        $media = $this->getMedia(self::PROFILE)->first();
        if (!empty($media)) {
            return $media->getFullUrl();
        }

        return asset('assets/img/infyom-logo.png');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var array
     */
    public static array $messages = [
        'email.regex' => 'Please enter valid email.',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'dob' => 'date',
        'gender' => 'integer',
        'country_id' => 'integer',
        'state_id' => 'integer',
        'city_id' => 'integer',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'phone' => 'string',
        'email_verified_at' => 'datetime',
        'owner_id' => 'integer',
        'owner_type' => 'string',
        'language' => 'string',
        'facebook_url' => 'string',
        'twitter_url' => 'string',
        'linkedin_url' => 'string',
        'google_plus_url' => 'string',
        'pinterest_url' => 'string',
        'is_default' => 'boolean',
        'region_code' => 'string',
    ];

    /**
     * @return string
     */
    public function getFullNameAttribute(): string {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * @return HasOne
     */
    public function candidate(): HasOne {
        return $this->hasOne(Candidate::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function company(): HasOne {
        return $this->hasOne(Company::class, 'user_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function candidateSkill(): BelongsToMany {
        return $this->belongsToMany(Skill::class, 'candidate_skills', 'user_id', 'skill_id');
    }

    /**
     * @return BelongsToMany
     */
    public function candidateLanguage(): BelongsToMany {
        return $this->belongsToMany(Language::class, 'candidate_language', 'user_id', 'language_id');
    }

    /**
     * @return HasMany
     */
    public function followings(): HasMany {
        return $this->hasMany(FavouriteCompany::class, 'user_id');
    }

    /**
     * @return bool
     */
    public function getIsOnlineProfileAvailbalAttribute(): bool {
        if (empty($this->facebook_url) && empty($this->twitter_url) && empty($this->linkedin_url) && empty($this->google_plus_url) && empty($this->pinterest_url)) {
            return false;
        }

        return true;
    }

    public function sendEmailVerificationNotification(): void {
        $this->notify(new UserVerifyNotification($this));  //pass the currently logged in user to the notification class
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void {
        $this->notify(new PasswordReset($token));
    }
}
