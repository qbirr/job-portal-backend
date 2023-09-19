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
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Job
 *
 * @property int $id
 * @property string $job_title
 * @property string $description
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $salary_from
 * @property string $salary_to
 * @property int $currency_id
 * @property int $salary_period_id
 * @property int $job_type_id
 * @property int $career_level_id
 * @property int $functional_area_id
 * @property int $job_shift_id
 * @property int $degree_level_id
 * @property int $position_id
 * @property string $job_expiry_date
 * @property int $no_preference
 * @property int $hide_salary
 * @property int $is_freelance
 * @property int $is_suspended
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CareerLevel $careerLevel
 * @property-read SalaryCurrency $currency
 * @property-read RequiredDegreeLevel $degreeLevel
 * @property-read FunctionalArea $functionalArea
 * @property-read JobShift $jobShift
 * @property-read JobType $jobType
 * @property-read Position $position
 * @property-read SalaryPeriod $salaryPeriod
 * @method static Builder|Job newModelQuery()
 * @method static Builder|Job newQuery()
 * @method static Builder|Job query()
 * @method static Builder|Job whereCareerLevelId($value)
 * @method static Builder|Job whereCity($value)
 * @method static Builder|Job whereCountry($value)
 * @method static Builder|Job whereCreatedAt($value)
 * @method static Builder|Job whereCurrencyId($value)
 * @method static Builder|Job whereDegreeLevelId($value)
 * @method static Builder|Job whereDescription($value)
 * @method static Builder|Job whereFunctionalAreaId($value)
 * @method static Builder|Job whereHideSalary($value)
 * @method static Builder|Job whereId($value)
 * @method static Builder|Job whereIsFreelance($value)
 * @method static Builder|Job whereIsFeatured($value)
 * @method static Builder|Job whereIsSuspended($value)
 * @method static Builder|Job whereJobExpiryDate($value)
 * @method static Builder|Job whereJobShiftId($value)
 * @method static Builder|Job whereJobTitle($value)
 * @method static Builder|Job whereJobTypeId($value)
 * @method static Builder|Job whereNoPreference($value)
 * @method static Builder|Job wherePositionId($value)
 * @method static Builder|Job whereSalaryFrom($value)
 * @method static Builder|Job whereSalaryPeriodId($value)
 * @method static Builder|Job whereSalaryTo($value)
 * @method static Builder|Job whereState($value)
 * @method static Builder|Job whereUpdatedAt($value)
 * @property int $company_id
 * @property-read Collection|JobApplication[] $appliedJobs
 * @property-read int|null $applied_jobs_count
 * @property-read Company $company
 * @property-read Collection|Skill[] $jobsSkill
 * @property-read int|null $jobs_skill_count
 * @method static Builder|Job whereCompanyId($value)
 * @property string $job_id
 * @property int $job_category_id
 * @property-read JobCategory $jobCategory
 * @method static Builder|Job whereJobCategoryId($value)
 * @method static Builder|Job whereJobId($value)
 * @method static Builder|Job status($status)
 * @property int $status
 * @method static Builder|Job whereStatus($value)
 * @property int|null $country_id
 * @property int|null $state_id
 * @property int|null $city_id
 * @property-read mixed $city_name
 * @property-read mixed $country_name
 * @property-read mixed $state_name
 * @method static Builder|Job whereCityId($value)
 * @method static Builder|Job whereCountryId($value)
 * @method static Builder|Job whereStateId($value)
 * @property int|null $experience
 * @property-read FeaturedRecord|null $activeFeatured
 * @property-read FeaturedRecord|null $featured
 * @property-read string $full_location
 * @property-read Collection|Tag[] $jobsTag
 * @property-read int|null $jobs_tag_count
 * @method static Builder|Job whereExperience($value)
 * @method static Builder|Job wherePosition($value)
 * @property int $is_created_by_admin
 * @method static Builder|Job whereIsCreatedByAdmin($value)
 * @property int $submission_status_id
 * @property int $is_default
 * @property int|null $last_change
 * @property-read User|null $admin
 * @property-read SubmissionStatus|null $submissionStatus
 * @method static Builder|Job whereIsDefault($value)
 * @method static Builder|Job whereLastChange($value)
 * @method static Builder|Job whereSubmissionStatusId($value)
 * @mixin Eloquent
 */
class Job extends Model {
    const NO_PREFERENCE = [
        2 => 'Both',
        1 => 'Male',
        0 => 'Female',
    ];

    const GENDER = [
        0 => 'Male',
        1 => 'Female',
    ];

    const IS_SUSPENDED = [
        1 => 'Yes',
        0 => 'No',
    ];

    const IS_FEATURED = [
        1 => 'Yes',
        0 => 'No',
    ];

    const STATUS_DRAFT = 0;

    const STATUS_OPEN = 1;

    const STATUS_CLOSED = 2;

    const STATUS_PAUSED = 3;

    const STATUS_SUSPENDED = 4;

    const NOT_SUSPENDED = 0;

    const STATUS = [
        0 => 'Drafted',
        1 => 'Live',
        2 => 'Closed',
        3 => 'Paused',
    ];

    const SUBMISSION_STATUS_PENDING = 1;
    const SUBMISSION_STATUS_APPROVED = 2;
    const SUBMISSION_STATUS_REJECTED = 3;
    const SUBMISSION_STATUS_RESUBMIT = 4;

    const FAVORITE_JOB_STATUS = [
        1 => 'Live',
        2 => 'Closed',
        3 => 'Paused',
    ];

    const STATUS_COLOR = [
        0 => 'warning',
        1 => 'success',
        2 => 'danger',
        3 => 'primary',
    ];

    const IS_FREELANCER = [
        1 => 'Yes',
        0 => 'No',
    ];

    const JOBS_ACTIVE = [
        0 => 'Active',
        1 => 'Expire',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static array $rules = [
        'company_id' => 'sometimes|required',
        'job_title' => 'required|max:180',
        'currency_id' => 'required',
        'salary_period_id' => 'required',
        'job_type_id' => 'required',
        'functional_area_id' => 'required',
        'position' => 'required|min:0|max:255',
        'experience' => 'required|min:0|max:255',
        'country_id' => 'required',
        'job_category_id' => 'required',
        'state_id' => 'required',
        'city_id' => 'required',
        'salary_from' => 'required|min:0|max:999999999',
        'salary_to' => 'required|min:0|max:999999999',
        'job_expiry_date' => 'required',
    ];

    public $table = 'jobs';

    public $fillable = [
        'job_id',
        'job_title',
        'description',
        'salary_from',
        'salary_to',
        'company_id',
        'job_category_id',
        'currency_id',
        'salary_period_id',
        'job_type_id',
        'career_level_id',
        'functional_area_id',
        'job_shift_id',
        'degree_level_id',
        'position',
        'experience',
        'job_expiry_date',
        'no_preference',
        'hide_salary',
        'is_freelance',
        'is_suspended',
        'country_id',
        'state_id',
        'city_id',
        'status',
        'is_created_by_admin',
        'last_change',
    ];

    /**
     * @var array
     */
    public $casts = [
        'id' => 'integer',
        'job_id' => 'string',
        'job_title' => 'string',
        'company_id' => 'integer',
        'job_category_id' => 'integer',
        'currency_id' => 'integer',
        'salary_period_id' => 'integer',
        'job_type_id' => 'integer',
        'career_level_id' => 'integer',
        'functional_area_id' => 'integer',
        'job_shift_id' => 'integer',
        'degree_level_id' => 'integer',
        'position' => 'integer',
        'experience' => 'integer',
        'salary_from' => 'double',
        'salary_to' => 'double',
        'country_id' => 'integer',
        'city_id' => 'integer',
        'state_id' => 'integer',
        'description' => 'string',
        'job_expiry_date' => 'date',
        'no_preference' => 'integer',
        'hide_salary' => 'boolean',
        'is_freelance' => 'boolean',
        'is_suspended' => 'boolean',
        'status' => 'integer',
        'is_created_by_admin' => 'integer',
        'last_change' => 'integer',
    ];

    protected $appends = ['country_name', 'state_name', 'city_name'];

    protected $with = ['country', 'state', 'city', 'activeFeatured'];

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

    public function admin(): HasOne {
        return $this->hasOne(User::class, 'id', 'last_change');
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
     * @return BelongsTo
     */
    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @param Builder $query
     * @param int $status
     * @return Builder
     */
    public function scopeStatus(Builder $query, int $status): Builder {
        return $query->where('status', $status);
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo {
        return $this->belongsTo(SalaryCurrency::class, 'currency_id');
    }

    /**
     * @return BelongsTo
     */
    public function salaryPeriod(): BelongsTo {
        return $this->belongsTo(SalaryPeriod::class, 'salary_period_id');
    }

    /**
     * @return BelongsTo
     */
    public function jobType(): BelongsTo {
        return $this->belongsTo(JobType::class, 'job_type_id');
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
     * @return BelongsTo
     */
    public function jobShift(): BelongsTo {
        return $this->belongsTo(JobShift::class, 'job_shift_id');
    }

    /**
     * @return BelongsTo
     */
    public function degreeLevel(): BelongsTo {
        return $this->belongsTo(RequiredDegreeLevel::class, 'degree_level_id');
    }

    /**
     * @return BelongsToMany
     */
    public function jobsSkill(): BelongsToMany {
        return $this->belongsToMany(Skill::class, 'jobs_skill', 'job_id', 'skill_id');
    }

    /**
     * @return BelongsToMany
     */
    public function jobsTag(): BelongsToMany {
        return $this->belongsToMany(Tag::class, 'jobs_tag', 'job_id', 'tag_id');
    }

    /**
     * @return HasMany
     */
    public function appliedJobs(): HasMany {
        return $this->hasMany(JobApplication::class, 'job_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function jobCategory(): BelongsTo {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }

    /**
     * @return string
     */
    public function getFullLocationAttribute(): string {
        $location = '';
        if (!empty($this->city)) {
            $location = $this->city->name . ', ';
        }

        if (!empty($this->state)) {
            $location = $location . $this->state->name . ', ';
        }

        if (!empty($this->country)) {
            $location = $location . $this->country->name;
        }

        return $location;
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
