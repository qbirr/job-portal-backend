<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Country;
use App\Models\FeaturedRecord;
use App\Models\FrontSetting;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\ReportedJob;
use App\Models\State;
use App\Models\Transaction;
use App\Repositories\JobRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laracasts\Flash\Flash;
use Throwable;

class JobController extends AppBaseController
{
    /** @var JobRepository */
    private $jobRepository;

    public function __construct(JobRepository $jobRepo)
    {
        $this->jobRepository = $jobRepo;
    }

    /**
     * Display a listing of the Job.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        $statusArray = Job::STATUS;

        if (! $this->checkJobLimit()) {
            Flash::error(__('messages.flash.job_create_limit'));
        }

        return view('employer.jobs.index', compact('statusArray'));
    }

    /**
     * Show the form for creating a new Job.
     *
     * @return Factory|View
     */
    public function create()
    {
        $data = $this->jobRepository->prepareData();

        return view('employer.jobs.create')->with('data', $data);
    }

    /**
     * Store a newly created Job in storage.
     *
     * @param  CreateJobRequest  $request
     * @return RedirectResponse|Redirector
     *
     * @throws Throwable
     */
    public function store(CreateJobRequest $request)
    {
        $input = $request->all();
        $input['hide_salary'] = (isset($input['hide_salary'])) ? 1 : 0;
        $input['is_freelance'] = (isset($input['is_freelance'])) ? 1 : 0;
        $input['status'] = (isset($request->saveAsDraft)) ? Job::STATUS_DRAFT : Job::STATUS_OPEN;
        if ($input['status'] == Job::STATUS_OPEN) {
            if (! $this->checkJobLimit()) {
                return redirect()->back()->withInput()->withErrors(['error' => __('messages.flash.job_create_limit')]);
            }
        }
        $job = $this->jobRepository->store($input);

        isset($request->saveDraft) ? Flash::success(__('messages.flash.job_draft')) : Flash::success(__('messages.flash.job_save'));

        return redirect(route('job.index'));
    }

    /**
     * Display the specified Job.
     *
     * @param  Job  $job
     * @return Factory|View
     */
    public function show(Job $job)
    {
        return view('employer.jobs.show')->with('job', $job);
    }

    /**
     * Show the form for editing the specified Job.
     *
     * @param  Job  $job
     * @return Factory|View
     */
    public function edit(Job $job)
    {
        if ($job->company->user->id !== getLoggedInUserId()) {
            return view('errors.404');
        }
//        if ($job->company->user->id !== getLoggedInUserId()) {
//            Flash::error(__('messages.flash.job_not_found'));
//
//            return redirect(route('job.index'));
//        }
        if ($job->status == Job::STATUS_CLOSED) {
            return redirect(route('job.index'))->withErrors(__('messages.flash.close_job'));
        }
        $data = $this->jobRepository->prepareData();
        $data['jobTags'] = $job->jobsTag()->pluck('tag_id')->toArray();
        $states = $cities = null;
        if (isset($job->country_id)) {
            $states = getStates($job->country_id);
        }
        if (isset($job->state_id)) {
            $cities = getCities($job->state_id);
        }

        return view('employer.jobs.edit', compact('data', 'job', 'cities', 'states'));
    }

    /**
     * Update the specified Job in storage.
     *
     * @param  Job  $job
     * @param  UpdateJobRequest  $request
     * @return RedirectResponse|Redirector
     *
     * @throws Throwable
     */
    public function update(Job $job, UpdateJobRequest $request)
    {
        if ($job->status != Job::STATUS_OPEN) {
            if (! $this->checkJobLimit()) {
                return redirect()->back()->withInput()->withErrors(['error' => __('messages.flash.job_create_limit')]);
            }
        }

        $input = $request->all();
        $input['hide_salary'] = (isset($input['hide_salary'])) ? 1 : 0;
        $input['is_freelance'] = (isset($input['is_freelance'])) ? 1 : 0;
        $job = $this->jobRepository->update($input, $job);

        Flash::success(__('messages.flash.job_update'));

        return redirect(route('job.index'));
    }

    /**
     * Remove the specified Job from storage.
     *
     * @param  Job  $job
     * @return RedirectResponse|Redirector
     *
     * @throws Exception
     */
    public function destroy(Job $job)
    {
        $userId = Auth::user()->owner_id;
        $companyId = Job::whereCompanyId($userId)->pluck('id')->toArray();

        if (! in_array($job->id, $companyId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $jobAppliedCount = $job->appliedJobs()->whereIn('status',
            [JobApplication::STATUS_APPLIED, JobApplication::STATUS_DRAFT])->count();
        if ($jobAppliedCount > 0) {
            return $this->sendError(__('messages.flash.job_apply_by_candidate'));
        }

        $this->jobRepository->delete($job->id);

        return $this->sendSuccess(__('messages.flash.job_delete'));
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function getStates(Request $request)
    {
        $postal = $request->get('postal');

        $states = getStates($postal);

        return $this->sendResponse($states, 'Retrieved successfully');
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function getCities(Request $request)
    {
        $state = $request->get('state');
        $cities = getCities($state);

        return $this->sendResponse($cities, 'Retrieved successfully');
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     *
     * @throws Exception
     */
    public function getJobs()
    {
        $featured = Job::IS_FEATURED;
        $suspended = Job::IS_SUSPENDED;
        $freelancer = Job::IS_FREELANCER;
        $jobsActiveExpire = Job::JOBS_ACTIVE;

        return view('jobs.index', compact('featured', 'suspended', 'freelancer', 'jobsActiveExpire'));
    }

    /**
     * @return Factory|View
     */
    public function createJob()
    {
        $data = $this->jobRepository->prepareData();
        $countries = Country::pluck('name', 'id');
        $states = State::toBase()->pluck('name', 'id');

        return view('jobs.create', compact('countries', 'states'))->with('data', $data);
    }

    /**
     * @param  CreateJobRequest  $request
     * @return RedirectResponse|Redirector
     *
     * @throws Throwable
     */
    public function storeJob(CreateJobRequest $request)
    {
        $input = $request->all();
        $input['hide_salary'] = (isset($input['hide_salary'])) ? 1 : 0;
        $input['is_freelance'] = (isset($input['is_freelance'])) ? 1 : 0;
        $input['status'] = Job::STATUS_OPEN;
        $this->jobRepository->store($input);

        Flash::success(__('messages.flash.job_save'));

        return redirect(route('admin.jobs.index'));
    }

    /**
     * Show the form for editing the specified Job.
     *
     * @param  Job  $job
     * @return Factory|View
     */
    public function editJob(Job $job)
    {
        if ($job->status == Job::STATUS_CLOSED) {
            Flash::error(__('messages.flash.close_job'));

            return redirect(route('admin.jobs.index'));
        }
        $data = $this->jobRepository->prepareData();
        $data['jobTags'] = $job->jobsTag()->pluck('tag_id')->toArray();
        $states = $cities = null;
        if (isset($job->country_id)) {
            $states = getStates($job->country_id);
        }
        if (isset($job->state_id)) {
            $cities = getCities($job->state_id);
        }
        $countries = Country::pluck('name', 'id');

        return view('jobs.edit', compact('data', 'job', 'cities', 'states', 'countries'));
    }

    /**
     * Update the specified Job in storage.
     *
     * @param  Job  $job
     * @param  UpdateJobRequest  $request
     * @return RedirectResponse|Redirector
     *
     * @throws Throwable
     */
    public function updateJob(Job $job, UpdateJobRequest $request)
    {
        $input = $request->all();
        $input['hide_salary'] = (isset($input['hide_salary'])) ? 1 : 0;
        $input['is_freelance'] = (isset($input['is_freelance'])) ? 1 : 0;

        $this->jobRepository->update($input, $job);

        Flash::success(__('messages.flash.job_update'));

        return redirect(route('admin.jobs.index'));
    }

    /**
     * Display the specified Job.
     *
     * @param  Job  $job
     * @return Factory|View
     */
    public function showJobs(Job $job)
    {
        $job = Job::with('company.user')->whereId($job->id)->first();

        return view('jobs.show')->with('job', $job);
    }

    /**
     * Remove the specified Job from storage.
     *
     * @param  Job  $job
     * @return RedirectResponse|Redirector
     *
     * @throws Exception
     */
    public function delete(Job $job)
    {
        $jobAppliedCount = $job->appliedJobs()->where('status', JobApplication::STATUS_APPLIED)->count();
        if ($jobAppliedCount > 0) {
            return $this->sendError(__('messages.flash.job_apply_by_candidate'));
        }

        $this->jobRepository->delete($job->id);

        return $this->sendSuccess(__('messages.flash.job_delete'));
    }

    /**
     * @param  Job  $job
     * @return mixed
     */
    public function changeIsSuspended(Job $job)
    {
        $isSuspended = $job->is_suspended;
        $job->update(['is_suspended' => ! $isSuspended]);

        if (Auth::user()->hasRole('Admin')) {
            $job->last_change = Auth::user()->id;
            $job->save();
        }

        return $this->sendSuccess(__('messages.flash.status_change'));
    }

    /**
     * @param  Request  $request
     * @return Application|Factory
     */
    public function showReportedJobs()
    {
        return view('employer.jobs.reported_jobs');
    }

    /**
     * @param $id
     * @param $status
     * @return mixed
     *
     * @throws Exception
     */
    public function changeJobStatus($id, $status)
    {
        /** @var Job $job */
        $job = Job::findOrFail($id);
        if ($job->status != Job::STATUS_OPEN && $status == Job::STATUS_OPEN) {
            if (! $this->checkJobLimit()) {
                return $this->sendError(__('messages.flash.job_create_limit'));
            }
        }

        $job->update(['status' => $status]);

        return $this->sendSuccess(__('messages.flash.status_change'));
    }

    /**
     * @param  ReportedJob  $reportedJob
     * @return mixed
     *
     * @throws Exception
     */
    public function deleteReportedJobs(ReportedJob $reportedJob)
    {
        $reportedJob->delete();

        return $this->sendSuccess(__('messages.flash.reported_job_delete'));
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function showReportedJobNote(ReportedJob $reportedJob)
    {
        $data = $this->jobRepository->getReportedJobs($reportedJob->id);
        $data['date'] = \Carbon\Carbon::parse($data->created_at)->formatLocalized('%d %b, %Y');

        return $this->sendResponse($data, 'Retrieved successfully.');
    }

    /**
     * @return RedirectResponse|bool
     *
     * @throws Exception
     */
    public function checkJobLimit()
    {
        $job = $this->jobRepository->canCreateMoreJobs();

        if (! $job) {
            return false;
        }

        return true;
    }

    /**
     * @param $jobId
     * @return mixed
     */
    public function makeFeatured($jobId)
    {
        $user = getLoggedInUser();
        $addDays = FrontSetting::where('key', 'featured_jobs_days')->first()->value;
        $price = FrontSetting::where('key', 'featured_jobs_price')->first()->value;
        $currency_id = FrontSetting::where('key', 'currency')->first()->value;
        $maxFeaturedJob = FrontSetting::where('key', 'featured_jobs_quota')->first()->value;
        $totalFeaturedJob = Job::Has('activeFeatured')->count();
        $isFeaturedAvailable = ($totalFeaturedJob >= $maxFeaturedJob) ? false : true;
        $employerUser = Job::with('company.user')->findOrFail($jobId);

        if ($isFeaturedAvailable) {
            $featuredRecord = [
                'owner_id' => $jobId,
                'owner_type' => Job::class,
                'user_id' => $user->id,
                'start_time' => Carbon::now(),
                'end_time' => Carbon::now()->addDays($addDays),
            ];
            FeaturedRecord::create($featuredRecord);
            $notificationStatus = NotificationSetting::where('key', '=', 'MARK_JOB_FEATURED')->pluck('value',
                'key')->toArray();
            if ($notificationStatus['MARK_JOB_FEATURED'] == Job::STATUS_OPEN) {
                NotificationSetting::where('key', 'MARK_JOB_FEATURED')->where('type',
                    'employer')->first()->value == 1 ?
                    addNotification([
                        Notification::MARK_JOB_FEATURED_ADMIN,
                        $employerUser->company->user->id,
                        Notification::EMPLOYER,
                        $user->first_name.' '.$user->last_name.' mark '.$employerUser->job_title.' as Featured.',
                    ]) : false;
            }
            if ($user->hasRole('Employer')) {
                addNotification([
                    Notification::MARK_JOB_FEATURED_ADMIN,
                    1,
                    3,
                    $employerUser->job_title.' is featured',
                ]);
            }
            $transaction = [
                'owner_id' => $jobId,
                'owner_type' => Job::class,
                'user_id' => $user->id,
                'plan_currency_id' => $currency_id,
                'amount' => $price,
            ];
            Transaction::create($transaction);

            if (Auth::user()->hasRole('Admin')) {
                $employerUser->last_change = Auth::user()->id;
                $employerUser->save();
            }

            return $this->sendSuccess(__('messages.flash.job_make_featured'));
        }

        return $this->sendError(__('messages.flash.featured_not_available'));
    }

    /**
     * @param $jobId
     * @return mixed
     */
    public function makeUnFeatured($jobId)
    {
        /** @var FeaturedRecord $unFeatured */
        $unFeatured = FeaturedRecord::where('owner_id', $jobId)->where('owner_type', Job::class)->first();
        $unFeatured->delete();

        $employerUser = Job::findOrFail($jobId);
        if ($employerUser) {
            if (Auth::user()->hasRole('Admin')) {
                $employerUser->last_change = Auth::user()->id;
                $employerUser->save();
            }
        }

        return $this->sendSuccess(__('messages.flash.job_make_unfeatured'));
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function getExpiredJobs()
    {
        return view('job_expired.index');
    }
}
