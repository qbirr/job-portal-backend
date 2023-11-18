<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\EmailJobToFriendRequest;
use App\Http\Requests\EmployerJobSearchRequest;
use App\Http\Requests\JobSearchRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use App\Repositories\JobRepository;
use App\Repositories\WebHomeRepository;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Throwable;

class JobController extends AppBaseController {
    public function __construct(
        private readonly JobRepository $jobRepository,
        private readonly WebHomeRepository $homeRepository,
    ) {
    }

    public function latestJobs() {
        return $this->jobRepository->latestJob();
    }

    public function searchJobAutocomplete(Request $request) {
        $searchTerm = strtolower($request->get('searchTerm'));
        return $this->homeRepository->jobSearch($searchTerm);
    }

    public function apiSearchJob(JobSearchRequest $request) {
        return response()->json($this->jobRepository->apiSearchJob($request), 200, [], JSON_NUMERIC_CHECK);
    }

    public function detail(?Job $job) {
        if (empty($job) || $job->status == Job::STATUS_DRAFT || $job->submission_status_id != Job::SUBMISSION_STATUS_APPROVED)
            return response()->json(null, 404);
        $job->load([
            'company', 'country', 'state', 'city', 'jobShift', 'jobsSkill', 'jobCategory', 'currency', 'jobsTag',
            'salaryPeriod', 'submissionStatus', 'degreeLevel', 'careerLevel', 'functionalArea',
            'company.industry', 'company.ownerShipType', 'company.user:id,first_name,last_name'
            ]);
        $data['resumes'] = null;

        $data['isActive'] = $data['isApplied'] = $data['isJobAddedToFavourite'] = $data['isJobReportedAsAbuse'] = false;
        if (Auth::check() && Auth::user()->hasRole('Candidate')) {
            $data = $this->jobRepository->getJobDetails($job);
        }
        $data['jobsCount'] = Job::whereStatus(Job::STATUS_OPEN)->whereCompanyId($job->company_id)->whereDate('job_expiry_date',
            '>=',
            Carbon::now()->toDateString())->count();

        // check job status is active or not
        $data['isActive'] = $job->status == Job::STATUS_OPEN;

        $relatedJobs = Job::with('jobCategory', 'jobShift', 'company')->whereJobCategoryId($job->job_category_id)
            ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString());
        $data['getRelatedJobs'] = $relatedJobs->whereNotIn('id', [$job->id])->orderByDesc('created_at')->take(6)->get();
        return [
            'job' => $job,
            'meta' => $data,
        ];
    }

    /**
     * @throws Throwable
     */
    public function update(Job $job, UpdateJobRequest $request) {
        if ($job->status != Job::STATUS_OPEN) {
            if (!$this->checkJobLimit()) {
                return $this->sendError(__('messages.flash.job_create_limit'));
            }
        }

        $input = $request->all();
        $input['hide_salary'] = (isset($input['hide_salary'])) ? 1 : 0;
        $input['is_freelance'] = (isset($input['is_freelance'])) ? 1 : 0;
        $job = $this->jobRepository->update($input, $job);

        return $this->sendSuccess(__('messages.flash.job_update'));
    }

    public function employerJobs(EmployerJobSearchRequest $request) {
        return $this->jobRepository->employerJobs(auth()->user()->company, $request);
    }

    public function emailJobToFriend(EmailJobToFriendRequest $request, Job $job) {
        $input = $request->all();
        $input = array_merge($input, [
            'user_id' => auth()->id(),
            'job_id' => $job->id,
            'job_url' => url('job-details/' . $job->job_id)
        ]);
        $this->jobRepository->emailJobToFriend($input);

        return $this->sendSuccess(__('messages.flash.job_emailed_to'));
    }

    public function reportJobAbuse(Request $request, Job $job) {
        $input = array_merge($request->all(),
            [
                'jobId' => $job->id,
                'userId' => auth()->id(),
            ]
        );
        $this->jobRepository->storeReportJobAbuse($input);

        return $this->sendSuccess(__('messages.flash.job_abuse_reported'));
    }

    /**
     * @throws Exception
     */
    public function checkJobLimit() {
        $job = $this->jobRepository->canCreateMoreJobs();

        if (!$job) {
            return false;
        }

        return true;
    }
}
