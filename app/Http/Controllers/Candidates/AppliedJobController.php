<?php

namespace App\Http\Controllers\Candidates;

use App\Http\Controllers\AppBaseController;
use App\Models\JobApplication;
use App\Models\JobApplicationSchedule;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class AppliedJobController extends AppBaseController {
    public function showScheduleSlotBook(JobApplication $jobApplication) {
        Gate::authorize('view', $jobApplication);

        /** @var JobApplicationSchedule $jobApplicationSchedules */
        $jobApplicationSchedules = JobApplicationSchedule::with([
            'jobApplication.job.company' => function ($query) {
                $query->without('job.company.user.city', 'job.company.user.state', 'job.company.user.country',
                    'job.company.user.media');
            },
        ])->whereJobApplicationId($jobApplication->id);

        $job = JobApplication::with([
            'candidate.user' => function ($query) {
                $query->without('user.media', 'user.city', 'user.state', 'user.country');
            },
        ], 'jobStage.company.user')->without('job')->whereId($jobApplication->id)->first();

        $data = [];

        foreach ($jobApplicationSchedules->get() as $jobApplicationSchedule) {
            $data['histories'][] = [
                'id' => $jobApplicationSchedule->id,
                'notes' => !empty($jobApplicationSchedule->notes) ? $jobApplicationSchedule->notes : __('messages.job_stage.new_slot_send'),
                'company_name' => $jobApplicationSchedule->jobApplication->job->company->user->full_name,
                'schedule_created_at' => Carbon::parse($jobApplicationSchedule->created_at)->translatedFormat('jS M Y, h:m A'),
            ];
        }
        $lastRecord = $jobApplicationSchedules->latest()->first();
        $data['rejectedSlot'] = $lastRecord->status == JobApplicationSchedule::STATUS_REJECTED;

        $allJobSchedule = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)
            ->where('batch', $lastRecord->batch)
            ->where('stage_id', $lastRecord->stage_id)
            ->get();

        if (!($allJobSchedule->where('status', JobApplicationSchedule::STATUS_SEND)->count() > 0)) {
            foreach ($allJobSchedule as $jobApplicationSchedule) {
                if ($jobApplicationSchedule->status == JobApplicationSchedule::STATUS_NOT_SEND) {
                    $data['slots'][] = [
                        'slot_id' => $jobApplicationSchedule->id,
                        'schedule_date' => Carbon::parse($jobApplicationSchedule->date)->translatedFormat('jS M Y'),
                        'schedule_time' => $jobApplicationSchedule->time,
                        'notes' => !empty($jobApplicationSchedule->notes) ? $jobApplicationSchedule->notes : __('messages.job_stage.new_slot_send'),
                        'isAllRejected' => $jobApplicationSchedule->status == JobApplicationSchedule::STATUS_REJECTED,
                    ];
                }
            }
        }
        $data['selectSlot'] = $allJobSchedule->where('status', JobApplicationSchedule::STATUS_SEND)->toArray();
        $employerCancelNote = $allJobSchedule->where('employer_cancel_slot_notes')->first();
        $data['employer_cancel_note'] = isset($employerCancelNote) ? $employerCancelNote->employer_cancel_slot_notes : '';
        $data['employer_fullName'] = $job->candidate->user->full_name;
        $data['company_fullName'] = !empty($job->jobStage->company) ? $job->jobStage->company->user->full_name : '';
        $data['isSlotRejected'] = $jobApplicationSchedules->where('status',
            JobApplicationSchedule::STATUS_REJECTED)->count();
        $data['scheduleSelect'] = $allJobSchedule->where('status', JobApplicationSchedule::STATUS_SEND)->count();

        return $this->sendResponse($data, __('messages.flash.job_schedule_send'));
    }

    public function choosePreference(Request $request, JobApplication $jobApplication) {
        Gate::authorize('view', $jobApplication);

        if (! isset($request->rejectSlot)) {
            $request->validate([
                'slot_id' => 'required',
            ], [
                'slot_id.required' => __('messages.flash.slot_preference_field'),
            ]);
        }

        $request->validate([
            'choose_slot_notes' => 'required',
        ], [
            'choose_slot_notes.required' => 'Notes Field is required',
        ]);

        $scheduleId = $request->get('slot_id');
        $slotNotes = $request->get('choose_slot_notes');

        if (! isset($request->rejectSlot)) {
            JobApplicationSchedule::whereId($scheduleId)->update(['status' => JobApplicationSchedule::STATUS_SEND, 'rejected_slot_notes' => $slotNotes]);
        } else {
            $jobApplicationSchedules = JobApplicationSchedule::whereJobApplicationId($jobApplication->id);
            $lastRecord = $jobApplicationSchedules->latest()->first();
            JobApplicationSchedule::where([
                ['job_application_id', $jobApplication->id],
                ['stage_id', $lastRecord->stage_id],
                ['batch', $lastRecord->batch],
                ['status', JobApplicationSchedule::STATUS_NOT_SEND],
            ])->update([
                'status' => JobApplicationSchedule::STATUS_REJECTED,
                'rejected_slot_notes' => $slotNotes,
            ]);
        }

        if (isset($request->rejectSlot)) {
            return $this->sendSuccess(__('messages.flash.slot_reject'));
        }

        return $this->sendSuccess(__('messages.flash.slot_choose'));
    }
}
