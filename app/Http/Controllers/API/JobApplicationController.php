<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\ApplyJobApiRequest;
use App\Models\Job;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Repositories\JobApplicationRepository;

class JobApplicationController extends AppBaseController {
    public function __construct(private readonly JobApplicationRepository $jobApplicationRepo) {
    }

    public function applyJob(ApplyJobApiRequest $request, Job $job) {
        $input = array_merge(
            $request->all(),
            [
                'job_id' => $job->id,
            ],
        );

        $this->jobApplicationRepo->store($input);

        $job->load((['company.user', 'appliedJobs']));
        $employerId = $job->company->user->id;

        $input['application_type'] != 'draft'
        && NotificationSetting::where('key', 'JOB_APPLICATION_SUBMITTED')->first()->value == 1
        && addNotification([
                Notification::JOB_APPLICATION_SUBMITTED,
                $employerId,
                Notification::EMPLOYER,
                'Job Application submitted for '.$job->job_title,
            ]);

        return $input['application_type'] == 'draft' ?
            $this->sendResponse($job->job_id, __('messages.flash.job_application_draft')) :
            $this->sendResponse($job->job_id, __('messages.flash.job_applied'));
    }
}
