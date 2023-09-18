<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobTypeRequest;
use App\Http\Requests\UpdateJobTypeRequest;
use App\Models\Job;
use App\Models\JobType;
use App\Repositories\JobTypeRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;

class JobTypeController extends AppBaseController
{
    /** @var JobTypeRepository */
    private $jobTypeRepository;

    public function __construct(JobTypeRepository $jobTypeRepo)
    {
        $this->jobTypeRepository = $jobTypeRepo;
    }

    /**
     * Display a listing of the JobType.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('job_types.index');
    }

    /**
     * Store a newly created JobType in storage.
     *
     * @param  CreateJobTypeRequest  $request
     * @return JsonResponse
     */
    public function store(CreateJobTypeRequest $request): JsonResponse
    {
        $input = $request->all();
        $jobType = $this->jobTypeRepository->create($input);

        return $this->sendResponse($jobType, __('messages.flash.job_type_save'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  JobType  $jobType
     * @return JsonResponse
     */
    public function edit(JobType $jobType)
    {
        return $this->sendResponse($jobType, 'Job Type Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified JobType.
     *
     * @param  JobType  $jobType
     * @return JsonResource
     */
    public function show(JobType $jobType)
    {
        return $this->sendResponse($jobType, __('messages.flash.job_type_retrieve'));
    }

    /**
     * Update the specified JobType in storage.
     *
     * @param  UpdateJobTypeRequest  $request
     * @param  JobType  $jobType
     * @return JsonResource
     */
    public function update(UpdateJobTypeRequest $request, JobType $jobType)
    {
        $input = $request->all();
        $this->jobTypeRepository->update($input, $jobType->id);

        return $this->sendSuccess(__('messages.flash.job_type_update'));
    }

    /**
     * Remove the specified JobType from storage.
     *
     * @param  JobType  $jobType
     * @return JsonResource
     *
     * @throws Exception
     */
    public function destroy(JobType $jobType)
    {
        $jobModels = [
            Job::class,
        ];
        $result = canDelete($jobModels, 'job_type_id', $jobType->id);
        if ($result) {
            return $this->sendError(__('messages.flash.job_type_cant_delete'));
        }
        $jobType->delete();

        return $this->sendSuccess(__('messages.flash.job_type_delete'));
    }
}
