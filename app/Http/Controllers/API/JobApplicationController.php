<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\ApplyJobApiRequest;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobApplicationSchedule;
use App\Models\JobStage;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Repositories\JobApplicationRepository;
use Arr;
use Exception;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function fetchSlot(Request $request, JobApplication $jobApplication) {
        Gate::authorize('view', $jobApplication);

        $getUniqueJobStages = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)
            ->toBase()->get()->unique('stage_id')
            ->pluck('stage_id')->toArray();

        /** @var JobStage $jobStage */
        $jobStage = JobStage::whereCompanyId(getLoggedInUser()->owner_id)->toBase()
            ->whereIn('id', $getUniqueJobStages)
            ->pluck('name', 'id');
        $lastStage = JobApplicationSchedule::latest()->first();

        /** @var JobApplicationSchedule $jobApplicationSchedules */
        $jobApplicationSchedules = JobApplicationSchedule::whereJobApplicationId($jobApplication->id);
        $lastRecord = $jobApplicationSchedules->latest()->first();

        /** @var JobApplication $jobApplicationStage */
        $jobApplicationStage = JobApplication::whereId($jobApplication->id)
            ->first();

        $isStageMatch = false;
        if (!empty($lastRecord)) {
            $isStageMatch = !($lastRecord->stage_id == $jobApplicationStage->job_stage_id);
        }

        $isSelectedRejectedSlot = 1;

        $jobSchedules = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)->get();

        if (isset($lastRecord)) {
            /** @var JobApplicationSchedule $isSelectedRejectedSlot */
            $isSelectedRejectedSlot = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)
                ->whereStageId($lastRecord->stage_id)
                ->whereBatch($lastRecord->batch)
                ->whereIn('status',
                    [JobApplicationSchedule::STATUS_SELECTED, JobApplicationSchedule::STATUS_REJECTED])
                ->count();
        }

        return $this
            ->sendResponse(compact('jobStage', 'lastStage', 'isSelectedRejectedSlot', 'isStageMatch', 'jobSchedules'),
                'Job Slot fetch successfully');
    }

    public function interviewSlotStore(Request $request, JobApplication $jobApplication) {
        try {
            DB::beginTransaction();
            $input = $request->json();
            $dates = Arr::pluck($input, 'date');
            $times = Arr::pluck($input, 'time');

            /** @var JobApplicationSchedule $lastJobSchedule */
            $lastJobSchedule = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)
                ->latest()->first();
            $lastJobScheduleExists = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)
                ->whereIn('date', $dates)
                ->whereIn('time', $times)
                ->exists();

            if ($lastJobScheduleExists) {
                return $this->sendError(__('messages.flash.slot_already_taken'));
            }

            $isPageReload = false;
            if (empty($lastJobSchedule)) {
                $batch = 1;
            } else {
                if ($lastJobSchedule['stage_id'] == $jobApplication->job_stage_id) {
                    $batch = $lastJobSchedule['batch'] + 1;
                    $isPageReload = false;
                } else {
                    $batch = 1;
                    $isPageReload = true;
                }
            }

            foreach ($input as $index => $slot) {
                if (isset($slot['time'])) {
                    if (count($times) > 1) {
                        $slotDates = Arr::except($dates, [$index]);
                        $slotHours = Arr::except($times, [$index]);
                        if (in_array($slot['date'], $slotDates))
                            if (in_array($slot['time'], $slotHours))
                                return $this->sendError(__('messages.flash.slot_already_taken'));
                        JobApplicationSchedule::create([
                            'job_application_id' => $jobApplication->id,
                            'time' => $slot['time'],
                            'date' => $slot['date'],
                            'notes' => $slot['notes'],
                            'status' => JobApplicationSchedule::STATUS_NOT_SEND,
                            'batch' => $batch,
                            'stage_id' => $jobApplication->job_stage_id,
                        ]);
                    }
                }
            }
            DB::commit();

            return $this->sendResponse($isPageReload, __('messages.flash.slot_create'));
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage(), 422);
        }
    }

    public function cancel(Request $request, JobApplicationSchedule $jobApplicationSchedule) {
        if (empty($request->get('cancelSlotNote'))) {
            return $this->sendError(__('messages.flash.cancel_reason_require'));
        }

        $cancelSlotNote = implode(',', $request->get('cancelSlotNote'));

        $jobApplicationSchedule->update([
            'status' => JobApplicationSchedule::STATUS_REJECTED,
            'employer_cancel_slot_notes' => $cancelSlotNote,
        ]);

        return $this->sendSuccess(__('messages.flash.slot_cancel'));
    }

    public function history(JobApplication $jobApplication) {
        Gate::authorize('view', $jobApplication);

        $jobApplicationSchedules = JobApplicationSchedule::with('jobApplication.candidate')
            ->where('job_application_id', $request->get('jobApplicationId'));
    }
}
