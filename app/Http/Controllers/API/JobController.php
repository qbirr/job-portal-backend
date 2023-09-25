<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Livewire\JobSearch;
use App\Http\Requests\JobSearchRequest;
use App\Models\Job;
use App\Repositories\JobRepository;
use App\Repositories\WebHomeRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JobController extends Controller {
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

    public function searchJob(JobSearchRequest $request) {
        return $this->jobRepository->searchJob($request);
    }

    public function detail(?Job $job) {
        if (empty($job) || $job->status == Job::STATUS_DRAFT || $job->submission_status_id != Job::SUBMISSION_STATUS_APPROVED)
            return response()->json(null, 404);
        $job->load(['jobsTag']);
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
}
