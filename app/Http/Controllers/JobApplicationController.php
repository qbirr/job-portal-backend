<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobApplicationSearchRequest;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobApplicationSchedule;
use App\Models\JobStage;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Repositories\JobApplicationRepository;
use Arr;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Class JobApplicationController
 */
class JobApplicationController extends AppBaseController {
    /** @var JobApplicationRepository */
    private JobApplicationRepository $jobApplicationRepository;

    /**
     * JobApplicationController constructor.
     *
     * @param JobApplicationRepository $jobApplicationRepo
     */
    public function __construct(JobApplicationRepository $jobApplicationRepo) {
        $this->jobApplicationRepository = $jobApplicationRepo;
    }

    /**
     * Display a listing of the Industry.
     *
     * @param int $jobId
     * @param Request $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(int $jobId, Request $request) {
        $userId = Auth::user()->owner_id;
        $companyId = Job::whereCompanyId($userId)->pluck('id')->toArray();

        if (!in_array($jobId, $companyId)) {
            return view('errors.404');
        }

        $input = $request->all();
        $input['job_id'] = $jobId;
        $job = Job::with('city')->findOrFail($jobId);
        $jobStage = JobStage::whereCompanyId(getLoggedInUser()->owner_id)->pluck('name', 'id');
        $statusArray = JobApplication::STATUS;

        return view('employer.job_applications.index', compact('jobId', 'statusArray', 'job', 'jobStage'));
    }

    public function fetch(JobApplicationSearchRequest $request, Job $job) {
        return $this->jobApplicationRepository->search($job->id, $request);
    }

    /**
     * Remove the specified Job Application from storage.
     *
     * @param JobApplication $jobApplication
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(JobApplication $jobApplication, Request $request) {
        $jobId = $request->get('jobId');
        $jobCandidateId = JobApplication::whereJobId($jobId)->pluck('id')->toArray();
        if (!in_array($jobApplication->id, $jobCandidateId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $this->jobApplicationRepository->delete($jobApplication->id);

        return $this->sendSuccess(__('messages.flash.job_application_delete'));
    }

    /**
     * @param    $id
     * @param $status
     * @param Request $request
     * @return mixed
     */
    public function changeJobApplicationStatus($id, $status, Request $request) {
        $jobId = $request->get('jobId');

        $jobCandidateId = JobApplication::whereJobId($jobId)->pluck('id')->toArray();

        if (!in_array($id, $jobCandidateId)) {
            return $this->sendError(__('messages.common.seems_message'));
        }

        $jobApplication = JobApplication::with(['candidate.user', 'job'])->findOrFail($id);
        $candidateUserId = $jobApplication->candidate->user->id;
        $jobTitle = $jobApplication->job->job_title;
        if (!in_array($jobApplication->status, [JobApplication::REJECTED, JobApplication::COMPLETE])) {
            $jobApplication->update(['status' => $status]);

            $status == JobApplication::REJECTED ? NotificationSetting::where('key', 'CANDIDATE_REJECTED_FOR_JOB')->first()->value == 1 ?
                addNotification([
                    Notification::CANDIDATE_REJECTED_FOR_JOB,
                    $candidateUserId,
                    Notification::CANDIDATE,
                    'Your application is Rejected for ' . $jobTitle,
                ]) : false : false;

            $status == JobApplication::COMPLETE ? NotificationSetting::where('key', 'CANDIDATE_SELECTED_FOR_JOB')->first()->value == 1 ?
                addNotification([
                    Notification::CANDIDATE_SELECTED_FOR_JOB,
                    $candidateUserId,
                    Notification::CANDIDATE,
                    'You are selected for ' . $jobTitle,
                ]) : false : false;

            $status == JobApplication::SHORT_LIST ? NotificationSetting::where('key', 'CANDIDATE_SHORTLISTED_FOR_JOB')->first()->value == 1 ?
                addNotification([
                    Notification::CANDIDATE_SHORTLISTED_FOR_JOB,
                    $candidateUserId,
                    Notification::CANDIDATE,
                    'Your application is Shortlisted for ' . $jobTitle,
                ]) : false : false;

            return $this->sendSuccess(__('messages.flash.status_change'));
        }

        return $this->sendError(JobApplication::STATUS[$jobApplication->status] . ' job cannot be ' . JobApplication::STATUS[$status]);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory
     */
    public function downloadMedia(Request $request) {
        try {
            $jobApplicationId = $request->jobApplication;
            $jobApplication = JobApplication::where('id', $jobApplicationId)->whereHas('job', function ($q) {
                $q->where('company_id', getLoggedInUser()->company->id);
            })->first();
            if ($jobApplication) {
                [$file, $headers] = $this->jobApplicationRepository->downloadMedia($jobApplication);

                return response($file, 200, $headers);
            } else {
                return view('errors.404');
            }
        } catch (Exception $e) {
            return view('errors.404');
        }
    }

    /**
     * @param JobApplication $jobApplication
     * @return JsonResponse
     */
    public function getJobApplicationDetail(JobApplication $jobApplication) {
        $jobApplication->load([
            'job:id,job_id,job_title,description,job_category_id,currency_id,salary_period_id,career_level_id,' .
            'functional_area_id,job_shift_id,degree_level_id,position,job_expiry_date,no_preference',
            'job.jobCategory', 'job.currency', 'job.salaryPeriod', 'job.careerLevel', 'job.functionalArea',
            'job.jobShift', 'job.degreeLevel',
            'candidate',
            'candidate.careerLevel', 'candidate.industry', 'candidate.functionalArea',
            'resume',
            'jobStage',
            ]);

        return $this->sendResponse($jobApplication, 'Job Application detail retrieve successfully.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeJobStage(Request $request) {
        $jobApplication = JobApplication::findOrFail($request->get('job_application_id'));
        $jobApplication->update(['job_stage_id' => $request->get('job_stage')]);

        return $this->sendSuccess(__('messages.flash.job_stage_change'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function viewSlotsScreen(Request $request) {
        try {
            $applicationId = $request->route('jobApplicationId');

            $CustomerJobId = JobApplication::where('id', $applicationId)->whereHas('job', function ($q) {
                $q->where('company_id', getLoggedInUser()->company->id);
            })->first();

            if ($CustomerJobId) {
                $getUniqueJobStages = JobApplicationSchedule::whereJobApplicationId($applicationId)
                    ->toBase()->get()->unique('stage_id')
                    ->pluck('stage_id')->toArray();

                /** @var JobStage $jobStage */
                $jobStage = JobStage::whereCompanyId(getLoggedInUser()->owner_id)->toBase()
                    ->whereIn('id', $getUniqueJobStages)
                    ->pluck('name', 'id');
                $lastStage = JobApplicationSchedule::latest()->first();

                /** @var JobApplicationSchedule $jobApplicationSchedules */
                $jobApplicationSchedules = JobApplicationSchedule::whereJobApplicationId($applicationId);
                $lastRecord = $jobApplicationSchedules->latest()->first();

                /** @var JobApplication $jobApplicationStage */
                $jobApplicationStage = JobApplication::whereId($applicationId)->first();

                $isStageMatch = false;
                if (!empty($lastRecord)) {
                    $isStageMatch = !($lastRecord->stage_id == $jobApplicationStage->job_stage_id);
                }

                $isSelectedRejectedSlot = 1;
                if (isset($lastRecord)) {
                    /** @var JobApplicationSchedule $isSelectedRejectedSlot */
                    $isSelectedRejectedSlot = JobApplicationSchedule::whereJobApplicationId($applicationId)
                        ->whereStageId($lastRecord->stage_id)
                        ->whereBatch($lastRecord->batch)
                        ->whereIn('status',
                            [JobApplicationSchedule::STATUS_SELECTED, JobApplicationSchedule::STATUS_REJECTED])
                        ->count();
                }

                return view('employer.job_applications.view_slot_screen',
                    compact('jobStage', 'lastStage', 'isSelectedRejectedSlot', 'isStageMatch', 'applicationId'));
            } else {
                return view('errors.404');
            }
        } catch (Exception $e) {
            return view('errors.404');
        }
    }

    /**
     * @param $jobId
     * @param Request $request
     * @return JsonResponse
     */
    public function interviewSlotStore($jobId, Request $request) {
        try {
            DB::beginTransaction();
            $input = $request->all();

            /** @var JobApplication $jobApplication */
            $jobApplication = JobApplication::whereId($input['job_application_id'])->first();

            /** @var JobApplicationSchedule $lastJobSchedule */
            $lastJobSchedule = JobApplicationSchedule::whereJobApplicationId($input['job_application_id'])
                ->latest()->first();
            $lastJobScheduleExists = JobApplicationSchedule::whereJobApplicationId($input['job_application_id'])
                ->whereIn('date', $input['date'])
                ->whereIn('time', $input['time'])
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

            for ($i = 1; $i <= $input['scheduleSlotCount']; $i++) {
                if (isset($input['time'][$i])) {
                    // validation date/time code
                    if (count($input['time']) > 1) {
                        $slotDates = Arr::except($input['date'], [$i]);
                        $slotHours = Arr::except($input['time'], [$i]);
                        if (in_array($input['date'][$i], $slotDates)) {
                            if (in_array($input['time'][$i], $slotHours)) {
                                return $this->sendError(__('messages.flash.slot_already_taken'));
                            }
                        }
                    }
                    JobApplicationSchedule::create([
                        'job_application_id' => $input['job_application_id'],
                        'time' => $input['time'][$i],
                        'date' => $input['date'][$i],
                        'notes' => $input['notes'][$i],
                        'status' => JobApplicationSchedule::STATUS_NOT_SEND,
                        'batch' => $batch,
                        'stage_id' => $jobApplication->job_stage_id,
                    ]);
                }
            }

            DB::commit();

            return $this->sendResponse($isPageReload, __('messages.flash.slot_create'));
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage(), 422);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function batchSlotStore(Request $request) {
        try {
            DB::beginTransaction();
            $input = $request->all();
            /** @var JobApplication $jobApplication */
            $jobApplication = JobApplication::whereId($input['job_application_id'])->first();

            $lastJobScheduleExists = JobApplicationSchedule::whereJobApplicationId($input['job_application_id'])
                ->where('date', $input['date'])
                ->where('time', $input['time'])
                ->exists();
            if ($lastJobScheduleExists) {
                return $this->sendError(__('messages.flash.slot_already_taken'));
            }

            JobApplicationSchedule::create([
                'job_application_id' => $input['job_application_id'],
                'time' => $input['time'],
                'date' => $input['date'],
                'notes' => $input['notes'],
                'status' => JobApplicationSchedule::STATUS_NOT_SEND,
                'batch' => $input['batch'],
                'stage_id' => $jobApplication->job_stage_id,
            ]);

            DB::commit();

            return $this->sendSuccess(__('messages.flash.slot_create'));
        } catch (Exception $e) {
            DB::rollBack();
            $this->sendError($e->getMessage(), 422);
        }
    }

    /**
     * @param $jobId
     * @param JobApplicationSchedule $slot
     * @return JsonResponse
     */
    public function editSlot($jobId, Request $request) {
        try {
            $slotId = $request->slot;
            $slot = JobApplicationSchedule::whereHas('jobApplication.job', function ($q) {
                $q->where('company_id', getLoggedInUser()->company->id);
            })->findorFail($slotId);

            if ($slot) {
                return $this->sendResponse($slot, 'Slot retrieved successfully');
            } else {
                return $this->sendError(__('messages.common.seems_message'));
            }
        } catch (Exception $e) {
            return $this->sendError(__('messages.common.seems_message'));
        }
    }

    /**
     * @param Request $request
     * @param $jobId
     * @param JobApplicationSchedule $slot
     * @return JsonResponse
     */
    public function updateSlot(Request $request, $jobId, JobApplicationSchedule $slot) {
        $input = $request->all();
        if ($input['time'] != $slot->time) {
            $isExist = JobApplicationSchedule::whereJobApplicationId($input['job_application_id'])
                ->where('date', $input['date'])
                ->where('time', $input['time'])
                ->exists();
            if ($isExist) {
                return $this->sendError(__('messages.flash.slot_already_taken'));
            }
        }
        $slot->update([
            'date' => $input['date'],
            'time' => $input['time'],
            'notes' => $input['notes'],
        ]);

        return $this->sendSuccess(__('messages.flash.slot_update'));
    }

    /**
     * @param $jobId
     * @param JobApplicationSchedule $slot
     * @return JsonResponse
     */
    public function slotDestroy($jobId, Request $request) {
        try {
            $slotId = $request->slot;
            $slot = JobApplicationSchedule::whereHas('jobApplication.job', function ($q) {
                $q->where('company_id', getLoggedInUser()->company->id);
            })->findorFail($slotId);

            if ($slot) {
                if ($slot->status == 1) {
                    return $this->sendError(__('messages.flash.assigned_slot_not_delete'));
                } else {
                    $slot->delete();

                    return $this->sendSuccess(__('messages.flash.slot_delete'));
                }
            } else {
                return $this->sendError(__('messages.common.seems_message'));
            }
        } catch (Exception $e) {
            return $this->sendError(__('messages.common.seems_message'));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getScheduleHistory(Request $request) {
        $jobApplicationSchedules = JobApplicationSchedule::with('jobApplication.candidate')
            ->where('job_application_id', $request->get('jobApplicationId'));

        $data = [];
        foreach ($jobApplicationSchedules->get() as $jobApplicationSchedule) {
            $data[] = [
                'notes' => !empty($jobApplicationSchedule->notes) ? $jobApplicationSchedule->notes : __('messages.job_stage.new_slot_send'),
                'company_name' => getLoggedInUser()->full_name,
                'schedule_date' => Carbon::parse($jobApplicationSchedule->date)->translatedFormat('jS M Y'),
                'schedule_time' => $jobApplicationSchedule->time,
                'status' => $jobApplicationSchedule->status,
                'rejected_slot_notes' => $jobApplicationSchedule->rejected_slot_notes,
                'created_at' => Carbon::parse($jobApplicationSchedule->created_at)->translatedFormat('jS M Y, h:m A'),
            ];
        }
        $rejectedSots = $jobApplicationSchedules->where('status', JobApplicationSchedule::STATUS_REJECTED)->get();
        foreach ($rejectedSots as $rejectSlot) {
            $data['candidate_name'] = $rejectSlot->jobApplication->candidate->user->full_name;
        }

        return $this->sendResponse($data, __('messages.flash.job_schedule_send'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelSelectedSlot(Request $request) {
        if (empty($request->get('cancelSlotNote'))) {
            return $this->sendError(__('messages.flash.cancel_reason_require'));
        }

        $cancelSlotNote = implode(',', $request->get('cancelSlotNote'));

        /** @var JobApplicationSchedule $jobApplicationSchedules */
        $jobApplicationSchedules = JobApplicationSchedule::whereId($request->get('slotId'))->first();
        $jobApplicationSchedules->update([
            'status' => JobApplicationSchedule::STATUS_REJECTED,
            'employer_cancel_slot_notes' => $cancelSlotNote,
        ]);

        return $this->sendSuccess(__('messages.flash.slot_cancel'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View
     *
     * @throws Exception
     */
    public function showAllSelectedCandidate() {
        $status = [JobApplication::COMPLETE => 'Hired', JobApplication::SHORT_LIST => 'Ongoing'];

        return view('selected_candidate.index', compact('status'));
    }

    /**
     * @param $jobId
     * @return JsonResponse
     */
    public function checkStage($jobApplicationId): JsonResponse {
        $data = [];
        $jobApplication = JobApplication::whereId($jobApplicationId)->first();
        $data['current_stage'] = $jobApplication->job_stage_id;
        $data['current_stage_cleared'] = JobApplicationSchedule::whereJobApplicationId($jobApplication->id)->whereStatus(JobApplicationSchedule::STATUS_SEND)->exists();
        $data['job_stages'] = JobStage::whereCompanyId(getLoggedInUser()->owner_id)->pluck('name', 'id');

        return $this->sendResponse($data, 'Job stages retrieved successfully');
    }
}
