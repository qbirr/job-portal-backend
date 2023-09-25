<?php

namespace App\Repositories;

use App\Http\Requests\JobSearchRequest;
use App\Models\Candidate;
use App\Models\CareerLevel;
use App\Models\Company;
use App\Models\EmailJob;
use App\Models\EmailTemplate;
use App\Models\FavouriteJob;
use App\Models\FrontSetting;
use App\Models\FunctionalArea;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobShift;
use App\Models\JobSubmissionLog;
use App\Models\JobType;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\Plan;
use App\Models\ReportedJob;
use App\Models\RequiredDegreeLevel;
use App\Models\SalaryCurrency;
use App\Models\SalaryPeriod;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LaravelIdea\Helper\App\Models\_IH_Job_C;
use PragmaRX\Countries\Package\Countries;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

/**
 * Class JobRepository
 *
 * @version July 12, 2020, 12:34 pm UTC
 */
class JobRepository extends BaseRepository {
    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'job_title',
        'is_freelance',
        'hide_salary',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string {
        return Job::class;
    }

    /**
     * @return array
     */
    public function prepareJobData(): array {

        $data['jobTypes'] = JobType::withCount(['jobs' => function ($q) {
            $q->whereStatus(Job::STATUS_OPEN)
                ->where('status', '!=', Job::STATUS_DRAFT)
                ->whereIsSuspended(Job::NOT_SUSPENDED)
                ->whereDate('job_expiry_date', '>=', Carbon::tomorrow()->toDateString());
        }])->toBase()->get();
        $data['jobCategories'] = JobCategory::toBase()->pluck('name', 'id');
        $data['jobSkills'] = Skill::toBase()->pluck('name', 'id');
        $data['genders'] = Job::NO_PREFERENCE;
        $data['careerLevels'] = CareerLevel::toBase()->pluck('level_name', 'id');
        $data['functionalAreas'] = FunctionalArea::toBase()->pluck('name', 'id');
        $data['advertise_image'] = FrontSetting::where('key', '=', 'advertise_image')->toBase()->first();

        return $data;
    }

    /**
     * @return array
     */
    public function prepareData(): array {
        $countries = new Countries();
        $data['jobType'] = JobType::pluck('name', 'id');
        $data['jobCategory'] = JobCategory::pluck('name', 'id');
        $data['careerLevels'] = CareerLevel::pluck('level_name', 'id');
        $data['jobShift'] = JobShift::orderBy('id')->pluck('shift', 'id');
        $data['currencies'] = SalaryCurrency::pluck('currency_name', 'id');
        $data['salaryPeriods'] = SalaryPeriod::pluck('period', 'id');
        $data['functionalArea'] = FunctionalArea::pluck('name', 'id');
        $data['preference'] = Job::NO_PREFERENCE;
        $data['jobSkill'] = Skill::pluck('name', 'id');
        $data['jobTag'] = Tag::pluck('name', 'id');
        $data['requiredDegreeLevel'] = RequiredDegreeLevel::pluck('name', 'id');
        $data['countries'] = getCountries();
        $data['companies'] = Company::with('user')->get()->where('user.is_active', '=', 1)
            ->pluck('user.full_name', 'id')->sort();

        logger($data['jobShift']);

        return $data;
    }

    /**
     * @return string
     */
    public function getUniqueJobId(): string {
        $jobUniqueId = Str::random(12);
        while (true) {
            $isExist = Job::whereJobId($jobUniqueId)->exists();
            if ($isExist) {
                self::getUniqueJobId();
            }
            break;
        }

        return $jobUniqueId;
    }

    /**
     * @param array $input
     * @return bool
     *
     * @throws Throwable
     */
    public function store(array $input): bool {
        try {
            DB::beginTransaction();

            $input['salary_from'] = (float)removeCommaFromNumbers($input['salary_from']);
            $input['salary_to'] = (float)removeCommaFromNumbers($input['salary_to']);
            $input['company_id'] = (isset($input['company_id'])) ? $input['company_id'] : Auth::user()->owner_id;
            $input['job_id'] = $this->getUniqueJobId();
            /** @var Job $job */
            if (isset($input['state_id']) && !is_numeric($input['state_id'])) {
                $input['state_id'] = null;
            }
            if (Auth::user()->hasRole('Admin')) {
                $input['is_created_by_admin'] = 1;
            }

            $job = $this->create($input);

            if (!empty($input['jobsSkill'])) {
                $job->jobsSkill()->sync($input['jobsSkill']);
            }
            if (!empty($input['jobTag'])) {
                $job->jobsTag()->sync($input['jobTag']);
            }
            $jobType = JobType::with('candidateJobAlerts')->whereId($input['job_type_id'])->first();
            $userIds = $jobType->candidateJobAlerts->where('job_alert', '=', 1)->pluck('user_id');
            $notificationAlertUserIds = $jobType->candidateJobAlerts->pluck('user_id');
            $users = User::whereIn('id', $userIds)->get();
            $notificationAlertUsers = User::whereIn('id', $notificationAlertUserIds)->get();
            if ($job->status != Job::STATUS_DRAFT) {
                $jobAlert = NotificationSetting::where('key', 'JOB_ALERT')->first()->value;
                foreach ($notificationAlertUsers as $user) {
                    $jobAlert == 1 ?
                        addNotification([
                            Notification::JOB_ALERT,
                            $user->id,
                            Notification::CANDIDATE,
                            'New job posted with ' . $job->job_title . ', if you are interested then you can apply for this job.',
                        ]) : false;
                }
                /** @var EmailTemplate $templateBody */
                $templateBody = EmailTemplate::whereTemplateName('Job Alert')->first();
                foreach ($users as $user) {
                    $job->name = $user->full_name;
                    $keyVariable = ['{{job_name}}', '{{job_url}}', '{{job_title}}', '{{from_name}}'];
                    $value = [$job->name, asset('/job-details/' . $job->job_id), $job->job_title, config('app.name')];
                    $body = str_replace($keyVariable, $value, $templateBody->body);
//                    $data['body'] = $body;
//                    Mail::to($user->email)->send(new EmailToCandidate($data));
                }
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param array $input
     * @param Job $job
     * @return bool|Builder|Builder[]|Collection|Model
     *
     * @throws Throwable
     */
    public function update($input, $job): Model|Collection|Builder|bool|array {
        try {
            DB::beginTransaction();
            $oldSubmissionStatusId = $job->submission_status_id;
            $input['salary_from'] = (float)removeCommaFromNumbers($input['salary_from']);
            $input['salary_to'] = (float)removeCommaFromNumbers($input['salary_to']);
            // update Job
            if (isset($input['state_id']) && !is_numeric($input['state_id'])) {
                $input['state_id'] = null;
            }
            if ($job->status == Job::STATUS_DRAFT) {
                $job->status = Job::STATUS_OPEN;
            }
            $job->update($input);

            if (!empty($input['jobsSkill'])) {
                $job->jobsSkill()->sync($input['jobsSkill']);
            }
            if (!empty($input['jobTag'])) {
                $job->jobsTag()->sync($input['jobTag']);
            } else {
                $job->jobsTag()->sync([]);
            }

            $company = $job->company;

            if (auth()->user()->id == $company->user->id && $job->submission_status_id == 3) {
                $job->update(['submission_status_id' => 4]);
                JobSubmissionLog::create([
                    'job_id' => $job->id,
                    'submission_status_id' => 4,
                    'notes' => '',
                    'user_id' => auth()->id()
                ]);

                addNotification([
                    Notification::JOB_APPLICATION_SUBMITTED,
                    User::role('Admin')->first()->id,
                    Notification::ADMIN,
                    "Employer {$company->user->email} change job data"
                ]);
            } elseif (isset($input['submission_status_id']) && auth()->user()->role('Admin') && $oldSubmissionStatusId != $input['submission_status_id']) {
                JobSubmissionLog::create([
                    'job_id' => $job->id,
                    'submission_status_id' => $input['submission_status_id'],
                    'notes' => $input['submission_notes'] ?? '',
                    'user_id' => auth()->id()
                ]);
                $message = match ((int) $input['submission_status_id']) {
                    2 => "Congratulation! Admin has approved Your job post titled: {$job->job_title}.",
                    3 => "We are sorry! Admin has rejected Your job post: {$input['submission_notes']}",
                    default => false,
                };
                if ($message)
                    addNotification([
                        Notification::JOB_APPLICATION_SUBMITTED,
                        $company->user->id,
                        Notification::EMPLOYER,
                        $message
                    ]);
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param int $jobId
     * @return bool
     */
    public function isJobAddedToFavourite(int $jobId): bool {
        return FavouriteJob::where('user_id', Auth::user()->id)->where('job_id', $jobId)->exists();
    }

    /**
     * @param int $jobId
     * @return bool
     */
    public function isJobReportedAsAbuse(int $jobId): bool {
        return ReportedJob::where('user_id', Auth::user()->id)->where('job_id', $jobId)->exists();
    }

    /**
     * @param Job $job
     * @return array
     */
    public function getJobDetails(Job $job): array {
        /** @var User $user */
        $user = Auth::user();

        /** @var Candidate $candidate */
        $candidate = Candidate::findOrFail($user->candidate->id);

        /** @var JobApplicationRepository $jobApplicationRepo */
        $jobApplicationRepo = app(JobApplicationRepository::class);

        // check candidate is already applied for job
        $data['isApplied'] = $jobApplicationRepo->checkJobStatus($job->id, $candidate->id,
            JobApplication::STATUS_APPLIED);

        // check job is drafted
        $data['isJobDrafted'] = $data['isJobApplicationRejected'] = $data['isJobApplicationCompleted'] = false;
        if (!$data['isApplied']) {
            // check job is drafted or not
            $data['isJobDrafted'] = $jobApplicationRepo->checkJobStatus($job->id, $candidate->id,
                JobApplication::STATUS_DRAFT);

            $data['isJobApplicationShortlisted'] = $jobApplicationRepo->checkJobStatus($job->id, $candidate->id,
                JobApplication::SHORT_LIST);

            $data['isJobApplicationRejected'] = $jobApplicationRepo->checkJobStatus($job->id, $candidate->id,
                JobApplication::REJECTED);

            $data['isJobApplicationCompleted'] = $jobApplicationRepo->checkJobStatus($job->id, $candidate->id,
                JobApplication::COMPLETE);
        }

        $data['isJobAddedToFavourite'] = $this->isJobAddedToFavourite($job->id);
        $data['isJobReportedAsAbuse'] = $this->isJobReportedAsAbuse($job->id);

        return $data;
    }

    /**
     * @param $input
     * @return bool
     */
    public function storeFavouriteJobs($input): bool {
        $job = Job::findOrFail($input['jobId']);
        $jobUser = Company::with('user')->findOrFail($job->company_id);
        $favouriteJob = FavouriteJob::where('user_id', $input['userId'])->where('job_id', $input['jobId'])->exists();
        if (!$favouriteJob) {
            FavouriteJob::create([
                'user_id' => $input['userId'],
                'job_id' => $input['jobId'],
            ]);
            $loggedInUser = getLoggedInUser();
            NotificationSetting::where('key', 'FOLLOW_JOB')->first()->value == 1 ?
                addNotification([
                    Notification::FOLLOW_JOB,
                    $jobUser->user->id,
                    Notification::EMPLOYER,
                    $loggedInUser->first_name . ' ' . $loggedInUser->last_name . ' started following ' . $job->job_title . '.',
                ]) : false;

            return true;
        } else {
            FavouriteJob::where('user_id', $input['userId'])->where('job_id', $input['jobId'])->delete();

            return false;
        }
    }

    /**
     * @param $input
     * @return bool
     */
    public function storeReportJobAbuse($input): bool {
        $jobReportedAsAbuse = ReportedJob::where('user_id', $input['userId'])->where('job_id',
            $input['jobId'])->exists();
        if (!$jobReportedAsAbuse) {
            $reportedJobNote = trim($input['note']);
            if (empty($reportedJobNote)) {
                throw ValidationException::withMessages([
                    'note' => 'The Note Field is required',
                ]);
            }
            ReportedJob::create([
                'user_id' => $input['userId'],
                'job_id' => $input['jobId'],
                'note' => $input['note'],
            ]);

            return true;
        }

        return false;
    }

    /**
     * @param $input
     * @return bool
     */
    public function emailJobToFriend($input): bool {
        try {
            DB::beginTransaction();

            /** @var EmailJob $emailJob */
            $emailJob = EmailJob::create($input);
            /** @var EmailTemplate $templateBody */
            $templateBody = EmailTemplate::whereTemplateName('Email Job To Friend')->first()['body'];
            $keyVariable = ['{{friend_name}}', '{{job_url}}', '{{from_name}}'];
            $value = [$emailJob->friend_name, $emailJob->job_url, config('app.name')];
            $body = str_replace($keyVariable, $value, $templateBody);
            $data['body'] = $body;
//            Mail::to($input['friend_email'])->send(new EmailJobToFriend($data));

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @return bool
     *
     * @throws Exception
     */
    public function canCreateMoreJobs(): bool {
        /** @var Company $company */
        $company = Company::whereUserId(Auth::id())->first();

        if ($company->user->email == 'employer@gmail.com') {
            return true;
        }

        /** @var SubscriptionRepository $subscriptionRepo */
        $subscriptionRepo = app(SubscriptionRepository::class);
        // retrieve user's subscription
        $subscription = $subscriptionRepo->getUserSubscription($company->user_id);

        if ($subscription) {
            // retrieve job count
            $jobCount = Job::whereStatus(Job::STATUS_OPEN)->where('company_id', $company->id)->where('is_created_by_admin', 0)->count();

            $maxJobCount = Plan::whereId($subscription->plan_id)->value('allowed_jobs');

            if ($maxJobCount <= 0 || $maxJobCount > $jobCount) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $reportedJobID
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function getReportedJobs($reportedJobID): Model|Collection|Builder|array|null {
        return ReportedJob::with(['user.candidate', 'job.company'])->without([
            'user.media', 'user.country', 'user.state', 'user.city',
        ])->select('reported_jobs.*')->orderBy('created_at',
            'desc')->findOrFail($reportedJobID);
    }

    public function searchJob(JobSearchRequest $request) {
        /** @var Job $query */
        $query = Job::with([
            'company', 'country', 'state', 'city', 'jobShift', 'jobsSkill', 'jobCategory',
        ])
            ->whereStatus(Job::STATUS_OPEN)->where('status', '!=', Job::STATUS_DRAFT)
            ->whereIsSuspended(Job::NOT_SUSPENDED)
            ->whereSubmissionStatusId(Job::SUBMISSION_STATUS_APPROVED)
            ->whereDate('job_expiry_date', '>=', Carbon::tomorrow()->toDateString());

        $query->when(!empty($request->types), function (Builder $q) use ($request) {
            $q->whereIn('job_type_id', $request->job_type_id);
        });

        $query->when(!empty($request->category), function (Builder $q) use ($request) {
            $q->where('job_category_id', '=', $request->job_category_id);
        });

        $query->when(!empty($request->salaryFrom), function (Builder $q) use ($request) {
            $q->where('salary_from', '>=', $request->salary_from);
        });

        $query->when(!empty($request->salaryTo), function (Builder $q) use ($request) {
            $q->where('salary_to', '<=', $request->salary_to);
        });

        $query->when(!empty($request->careerLevel), function (Builder $q) use ($request) {
            $q->where('career_level_id', '=', $request->career_level_id);
        });

        $query->when(!empty($request->functionalArea), function (Builder $q) use ($request) {
            $q->where('functional_area_id', '=', $request->functional_area_id);
        });

        $query->when($request->no_preference != '', function (Builder $q) use ($request) {
            $q->where('no_preference', '=', $request->no_preference);
        });

        $query->when(!empty($request->skill), function (Builder $q) use ($request) {
            $q->whereHas('jobsSkill', function (Builder $q) use ($request) {
                $q->where('skill_id', '=', $request->skill_id);
            });
        });
        $query->when(!empty($request->company), function (Builder $q) use ($request) {
            $q->whereHas('company', function (Builder $q) use ($request) {
                $q->where('company_id', '=', $request->company_id);
            });
        });

        $query->when(!empty($request->jobExperience), function (Builder $q) use ($request) {
            $q->where('experience', '=', $request->experience);
        });

        $query->when(!empty($request->featuredJob), function (Builder|Job $q) use ($request) {
            $q->has('activeFeatured')
                ->whereStatus(Job::STATUS_OPEN)
                ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
                ->where('is_suspended', '=', Job::NOT_SUSPENDED);
        });

        $query->when(!empty($request->searchByLocation), function (Builder $q) use ($request) {
            $q->where(function (Builder $q) use ($request) {
                $q->where('job_title', 'like', '%' . $request->location . '%');
                $q->orWhereHas(
                    'country',
                    function (Builder $q) use ($request) {
                        $q->where('name', 'like', '%' . $request->location . '%');
                    }
                )->orWhereHas(
                    'state',
                    function (Builder $q) use ($request) {
                        $q->where('name', 'like', '%' . $request->location . '%');
                    }
                )->orWhereHas(
                    'city',
                    function (Builder $q) use ($request) {
                        $q->where('name', 'like', '%' . $request->location . '%');
                    }
                )->orWhereHas(
                    'company.user',
                    function (Builder $q) use ($request) {
                        $q->where('first_name', 'like', '%' . $request->location . '%')
                            ->orWhere('last_name', 'like', '%' . $request->location . '%');
                    }
                )->orWhereHas(
                    'jobsSkill',
                    function (Builder $q) use ($request) {
                        $q->where('name', 'like', '%' . $request->location . '%');
                    }
                );
            });
        });

        $query->when(!empty($request->title), function (Builder $q) use ($request) {
            $q->where('job_title', 'like', '%' . $request->title . '%')
                ->orWhereHas('jobsSkill', function (Builder $q) use ($request) {
                    $q->where('name', 'like', '%' . $request->title . '%');
                })
                ->orWhereHas('company.user', function (Builder $q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->title . '%')
                        ->orWhere('last_name', 'like', '%' . $request->title . '%');
                });
        });

        $all = $query->paginate($request->perPage);
        $currentPage = $all->currentPage();
        $lastPage = $all->lastPage();
        if ($currentPage > $lastPage) {
            $request->page = $lastPage;
            $all = $query->paginate($request->perPage);
        }

        return $all;
    }

    public function latestJob(): Collection|_IH_Job_C|array {
        $featureJobs = Job::has('activeFeatured')
            ->whereStatus(Job::STATUS_OPEN)
            ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
            ->where('is_suspended', '=', Job::NOT_SUSPENDED)
            ->with(['company', 'jobCategory', 'jobsSkill', 'activeFeatured'])
            ->orderBy('created_at', 'desc')
            ->get();
        $latestJobs = Job::whereStatus(Job::STATUS_OPEN)
            ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
            ->where('is_suspended', '=', Job::NOT_SUSPENDED)
            ->with(['company', 'jobCategory', 'jobsSkill'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        return $latestJobs->merge($featureJobs);
    }
}
