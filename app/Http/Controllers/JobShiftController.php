<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobShiftRequest;
use App\Http\Requests\UpdateJobShiftRequest;
use App\Models\Job;
use App\Models\JobShift;
use App\Repositories\JobShiftRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;

class JobShiftController extends AppBaseController
{
    /** @var JobShiftRepository */
    private $jobShiftRepository;

    public function __construct(JobShiftRepository $jobShiftRepo)
    {
        $this->jobShiftRepository = $jobShiftRepo;
    }

    /**
     * Display a listing of the JobShift.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('job_shifts.index');
    }

    /**
     * Store a newly created JobShift in storage.
     *
     * @param  CreateJobShiftRequest  $request
     * @return JsonResponse
     */
    public function store(CreateJobShiftRequest $request): JsonResponse
    {
        $input = $request->all();
        $jobShift = $this->jobShiftRepository->create($input);

        return $this->sendResponse($jobShift, __('messages.flash.job_shift_save'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  JobShift  $jobShift
     * @return JsonResponse
     */
    public function edit(JobShift $jobShift)
    {
        return $this->sendResponse($jobShift, 'Job Shift Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified JobShift.
     *
     * @param  JobShift  $jobShift
     * @return JsonResource
     */
    public function show(JobShift $jobShift)
    {
        return $this->sendResponse($jobShift, __('messages.flash.job_shift_retrieve'));
    }

    /**
     * Update the specified JobShift in storage.
     *
     * @param  UpdateJobShiftRequest  $request
     * @param  JobShift  $jobShift
     * @return JsonResource
     */
    public function update(UpdateJobShiftRequest $request, JobShift $jobShift)
    {
        $input = $request->all();
        $this->jobShiftRepository->update($input, $jobShift->id);

        return $this->sendSuccess(__('messages.flash.job_shift_update'));
    }

    /**
     * Remove the specified JobShift from storage.
     *
     * @param  JobShift  $jobShift
     * @return JsonResource
     *
     * @throws Exception
     */
    public function destroy(JobShift $jobShift)
    {
        $jobModels = [
            Job::class,
        ];
        $result = canDelete($jobModels, 'job_shift_id', $jobShift->id);
        if ($result) {
            return $this->sendError(__('messages.flash.job_shift_cant_delete'));
        }
        $jobShift->delete();

        return $this->sendSuccess(__('messages.flash.job_shift_delete'));
    }
}
