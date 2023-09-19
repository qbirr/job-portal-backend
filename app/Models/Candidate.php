<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Candidate
 *
 * @version July 20, 2020, 5:48 am UTC
 * @property int $id
 * @property int $user_id
 * @property string $unique_id
 * @property string|null $father_name
 * @property int $marital_status_id
 * @property string|null $nationality
 * @property string|null $national_id_card
 * @property int|null $experience
 * @property int|null $career_level_id
 * @property int|null $industry_id
 * @property int|null $functional_area_id
 * @property float|null $current_salary
 * @property float|null $expected_salary
 * @property string|null $salary_currency
 * @property string|null $address
 * @property int $immediate_available
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CareerLevel|null $careerLevel
 * @property-read FunctionalArea|null $functionalArea
 * @property-read Industry|null $industry
 * @property-read MaritalStatus $maritalStatus
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 * @property-read User $user
 * @property-read mixed $candidate_url
 * @method static Builder|Candidate newModelQuery()
 * @method static Builder|Candidate newQuery()
 * @method static Builder|Candidate query()
 * @method static Builder|Candidate whereAddress($value)
 * @method static Builder|Candidate whereCareerLevelId($value)
 * @method static Builder|Candidate whereCreatedAt($value)
 * @method static Builder|Candidate whereCurrentSalary($value)
 * @method static Builder|Candidate whereExpectedSalary($value)
 * @method static Builder|Candidate whereExperience($value)
 * @method static Builder|Candidate whereFatherName($value)
 * @method static Builder|Candidate whereFunctionalAreaId($value)
 * @method static Builder|Candidate whereId($value)
 * @method static Builder|Candidate whereImmediateAvailable($value)
 * @method static Builder|Candidate whereIndustryId($value)
 * @method static Builder|Candidate whereMaritalStatusId($value)
 * @method static Builder|Candidate whereNationalIdCard($value)
 * @method static Builder|Candidate whereNationality($value)
 * @method static Builder|Candidate whereSalaryCurrency($value)
 * @method static Builder|Candidate whereUpdatedAt($value)
 * @method static Builder|Candidate whereUserId($value)
 * @method static Builder|Candidate whereUniqueId($value)
 * @property int $job_alert
 * @property-read mixed $city_name
 * @property-read mixed $country_name
 * @property-read string $full_location
 * @property-read mixed $state_name
 * @property-read Collection|JobType[] $jobAlerts
 * @property-read int|null $job_alerts_count
 * @property-read Collection|JobApplication[] $jobApplications
 * @property-read int|null $job_applications_count
 * @property-read Collection|JobApplication[] $pendingJobApplications
 * @property-read int|null $pending_job_applications_count
 * @method static Builder|Candidate whereJobAlert($value)
 * @property string|null $available_at
 * @method static Builder|Candidate whereAvailableAt($value)
 * @property int|null $last_change
 * @property-read User|null $admin
 * @method static Builder|Candidate whereLastChange($value)
 * @mixin Eloquent
 */
class Candidate extends Model implements HasMedia {
    use InteractsWithMedia;

    public $table = 'candidates';

    const RESUME_PATH = 'resumes';

    public const CANDIDATE_LOGIN_TYPE = 1;

    public const CANDIDATE_EMP_TYPE = 2;

    const STATUS = [
        1 => 'Active',
        0 => 'Deactive',
    ];

    const IMMEDIATE_AVAILABLE = [
        1 => 'Immediate Available',
        0 => 'Not Immediate Available',
    ];

    public $fillable = [
        'user_id',
        'unique_id',
        'father_name',
        'marital_status_id',
        'nationality',
        'national_id_card',
        'experience',
        'career_level_id',
        'industry_id',
        'functional_area_id',
        'current_salary',
        'expected_salary',
        'salary_currency',
        'address',
        'immediate_available',
        'available_at',
        'last_change',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'unique_id' => 'string',
        'father_name' => 'string',
        'marital_status_id' => 'integer',
        'nationality' => 'string',
        'national_id_card' => 'string',
        'experience' => 'integer',
        'career_level_id' => 'integer',
        'industry_id' => 'integer',
        'functional_area_id' => 'integer',
        'current_salary' => 'double',
        'expected_salary' => 'double',
        'salary_currency' => 'string',
        'address' => 'string',
        'immediate_available' => 'boolean',
        'available_at' => 'date',
        'last_change' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static array $rules = [
        'first_name' => 'required|max:180',
        'last_name' => 'required|max:180',
        'email' => 'required|email:filter|unique:users,email',
        'password' => 'nullable|same:password_confirmation|min:6',
        'gender' => 'required',
        'dob' => 'nullable|date',
        'current_salary' => 'nullable|numeric|min:0|max:999999999',
        'expected_salary' => 'nullable|numeric|min:0|max:999999999',
        'phone' => 'nullable',
        'marital_status_id' => 'sometimes|nullable',
    ];

    protected $appends = ['country_name', 'state_name', 'city_name', 'full_location', 'candidate_url'];

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
    public function getFullLocationAttribute(): ?string {
        $location = '';
        if (!empty($this->user->country)) {
            $location = $this->user->country->name;
        }
        if (!empty($this->user->state)) {
            $location = $location . ',' . $this->user->state->name;
        }
        if (!empty($this->user->city)) {
            $location = $location . ',' . $this->user->city->name;
        }

        return (!empty($location)) ? $location : null;
    }

    /**
     * @return mixed
     */
    public function getCandidateUrlAttribute(): mixed {
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
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(): HasOne {
        return $this->hasOne(User::class, 'id', 'last_change');
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
    public function maritalStatus(): BelongsTo {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    /**
     * @return BelongsTo
     */
    public function careerLevel(): BelongsTo {
        return $this->belongsTo(CareerLevel::class, 'career_level_id');
    }

    /**
     * @return BelongsTo
     */
    public function functionalArea(): BelongsTo {
        return $this->belongsTo(FunctionalArea::class, 'functional_area_id');
    }

    /**
     * @return BelongsToMany
     */
    public function jobAlerts(): BelongsToMany {
        return $this->belongsToMany(JobType::class, 'jobs_alerts', 'candidate_id', 'job_type_id');
    }

    /**
     * @return HasMany
     */
    public function jobApplications(): HasMany {
        return $this->hasMany(JobApplication::class, 'candidate_id');
    }

    /**
     * @return HasMany
     */
    public function pendingJobApplications(): HasMany {
        return $this->hasMany(JobApplication::class, 'candidate_id')->where('status', JobApplication::STATUS_APPLIED);
    }
}
