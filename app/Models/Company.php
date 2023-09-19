<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Company
 *
 * @version June 22, 2020, 12:34 pm UTC
 * @property int $id
 * @property string $ceo
 * @property int $no_of_offices
 * @property int $industry_id
 * @property int $ownership_type_id
 * @property int $company_size_id
 * @property int $established_in
 * @property string|null $details
 * @property string $website
 * @property string $unique_id
 * @property string $location
 * @property string $location2
 * @property string|null $fax
 * @property string|null $facebook_url
 * @property string|null $twitter_url
 * @property string|null $linkedin_url
 * @property string|null $google_plus_url
 * @property string|null $pinterest_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CompanySize $companySize
 * @property-read Industry $industry
 * @property-read OwnerShipType $ownerShipType
 * @method static Builder|Company newModelQuery()
 * @method static Builder|Company newQuery()
 * @method static Builder|Company query()
 * @method static Builder|Company whereCeo($value)
 * @method static Builder|Company whereCompanySizeId($value)
 * @method static Builder|Company whereCreatedAt($value)
 * @method static Builder|Company whereDetails($value)
 * @method static Builder|Company whereEstablishedIn($value)
 * @method static Builder|Company whereFacebookUrl($value)
 * @method static Builder|Company whereFax($value)
 * @method static Builder|Company whereGooglePlusUrl($value)
 * @method static Builder|Company whereId($value)
 * @method static Builder|Company whereIndustryId($value)
 * @method static Builder|Company whereIsFeatured($value)
 * @method static Builder|Company whereLinkedinUrl($value)
 * @method static Builder|Company whereLocation($value)
 * @method static Builder|Company whereLocation2($value)
 * @method static Builder|Company whereNoOfOffices($value)
 * @method static Builder|Company whereOwnershipTypeId($value)
 * @method static Builder|Company wherePinterestUrl($value)
 * @method static Builder|Company whereTwitterUrl($value)
 * @method static Builder|Company whereUpdatedAt($value)
 * @method static Builder|Company whereWebsite($value)
 * @method static Builder|Company whereUniqueId($value)
 * @property-read User|null $user
 * @property int|null $user_id
 * @property-read mixed $company_url
 * @property-read Collection|Job[] $jobs
 * @property-read int|null $jobs_count
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 * @method static Builder|Company whereUserId($value)
 * @property-read FeaturedRecord|null $activeFeatured
 * @property-read FeaturedRecord|null $featured
 * @property-read mixed $city_name
 * @property-read mixed $country_name
 * @property-read mixed $state_name
 * @property int $submission_status_id
 * @property int $is_featured
 * @property int|null $last_change
 * @property-read User|null $admin
 * @property-read SubmissionStatus|null $submissionStatus
 * @method static Builder|Company whereLastChange($value)
 * @method static Builder|Company whereSubmissionStatusId($value)
 * @mixin Eloquent
 */
class Company extends Model implements HasMedia {
    use InteractsWithMedia;

    public $table = 'companies';

    public const COMPANY_LOGIN_TYPE = 0;

    public const ISACTIVE = 1;

    public const DEACTIVE = 0;

    const BTN_BTN_COLOR = [
        'btn btn-green btn-small-effect',
        'btn btn-purple btn-small btn-effect',
        'btn btn-blue btn-small btn-effect',
        'btn btn-orange btn-small btn-effect',
        'btn btn-red btn-small btn-effect',
        'btn btn-blue-grey btn-small btn-effect',
        'btn btn-green btn-small btn-effect',
    ];

    const IS_FEATURED = [
        1 => 'Yes',
        0 => 'No',
    ];

    const STATUS = [
        1 => 'Active',
        0 => 'Deactive',
    ];

    public $fillable = [
        'ceo',
        'industry_id',
        'ownership_type_id',
        'company_size_id',
        'established_in',
        'details',
        'website',
        'location',
        'location2',
        'no_of_offices',
        'fax',
        'user_id',
        'unique_id',
        'last_change',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ceo' => 'string',
        'industry_id' => 'integer',
        'ownership_type_id' => 'integer',
        'company_size_id' => 'integer',
        'established_in' => 'integer',
        'details' => 'string',
        'website' => 'string',
        'location' => 'string',
        'location2' => 'string',
        'no_of_offices' => 'integer',
        'fax' => 'string',
        'user_id' => 'integer',
        'unique_id' => 'string',
        'last_change' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'ceo' => 'required|max:180',
        'industry_id' => 'required',
        'ownership_type_id' => 'required',
//        'company_size_id' => 'required',
        'established_in' => 'required',
        'website' => 'nullable|url',
        'location' => 'required',
        'no_of_offices' => 'required|numeric|min:1|max:1000',
    ];

    /**
     * @var array
     */
    protected $appends = ['company_url'];

    protected $with = ['user'];

    public function getCountryNameAttribute(): ?string {
        if (!empty($this->user->country)) {
            return $this->user->country->name;
        }
        return null;
    }

    public function getStateNameAttribute(): ?string {
        if (!empty($this->user->state)) {
            return $this->user->state->name;
        }
        return null;
    }

    public function getCityNameAttribute(): ?string {
        if (!empty($this->user->city)) {
            return $this->user->city->name;
        }
        return null;
    }

    /**
     * @return string
     */
    public function getCompanyUrlAttribute(): string {
        /** @var Media $media */
        $media = $this->user->getMedia(User::PROFILE)->first();
        if (!empty($media)) {
            return $media->getFullUrl();
        }

        return asset('assets/img/employer-image.png');
    }

    /**
     * @return BelongsTo
     */
    public function industry(): BelongsTo {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    /**
     * @return BelongsTo
     */
    public function ownerShipType(): BelongsTo {
        return $this->belongsTo(OwnerShipType::class, 'ownership_type_id');
    }

    public function admin(): HasOne {
        return $this->hasOne(User::class, 'id', 'last_change');
    }

    /**
     * @return BelongsTo
     */
    public function companySize(): BelongsTo {
        return $this->belongsTo(CompanySize::class, 'company_size_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function jobs(): HasMany {
        return $this->hasMany(Job::class, 'company_id');
    }

    /**
     * @return MorphOne
     */
    public function featured(): MorphOne {
        return $this->morphOne(FeaturedRecord::class, 'owner');
    }

    /**
     * @return MorphOne
     */
    public function activeFeatured(): MorphOne {
        return $this->morphOne(FeaturedRecord::class, 'owner')->where('end_time', '>', \Carbon\Carbon::now());
    }

    public function submissionStatus(): BelongsTo {
        return $this->belongsTo(SubmissionStatus::class);
    }
}
