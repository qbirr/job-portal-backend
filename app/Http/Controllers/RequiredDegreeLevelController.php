<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequiredDegreeLevelRequest;
use App\Http\Requests\UpdateRequiredDegreeLevelRequest;
use App\Models\Job;
use App\Models\RequiredDegreeLevel;
use App\Repositories\RequiredDegreeLevelRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RequiredDegreeLevelController extends AppBaseController
{
    /** @var RequiredDegreeLevelRepository */
    private $requiredDegreeLevelRepository;

    public function __construct(RequiredDegreeLevelRepository $requiredDegreeLevelRepo)
    {
        $this->requiredDegreeLevelRepository = $requiredDegreeLevelRepo;
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
        return view('required_degree_levels.index');
    }

    /**
     * Store a newly created RequiredDegreeLevel in storage.
     *
     * @param  CreateRequiredDegreeLevelRequest  $request
     * @return JsonResponse
     */
    public function store(CreateRequiredDegreeLevelRequest $request)
    {
        $input = $request->all();
        $degreeLevel = $this->requiredDegreeLevelRepository->create($input);

        return $this->sendResponse($degreeLevel, __('messages.flash.degree_level_save'));
    }

    /**
     * Display the specified RequiredDegreeLevel.
     *
     * @param  RequiredDegreeLevel  $requiredDegreeLevel
     * @return Response
     */
    public function show(RequiredDegreeLevel $requiredDegreeLevel)
    {
        return $this->sendResponse($requiredDegreeLevel, __('messages.flash.degree_level_retrieve'));
    }

    /**
     * Show the form for editing the specified RequiredDegreeLevel.
     *
     * @param  RequiredDegreeLevel  $requiredDegreeLevel
     * @return Response
     */
    public function edit(RequiredDegreeLevel $requiredDegreeLevel)
    {
        return $this->sendResponse($requiredDegreeLevel, 'Degree Level Successfully.');
    }

    /**
     * Update the specified RequiredDegreeLevel in storage.
     *
     * @param  UpdateRequiredDegreeLevelRequest  $request
     * @param  RequiredDegreeLevel  $requiredDegreeLevel
     * @return Response
     */
    public function update(UpdateRequiredDegreeLevelRequest $request, RequiredDegreeLevel $requiredDegreeLevel)
    {
        $input = $request->all();
        $this->requiredDegreeLevelRepository->update($input, $requiredDegreeLevel->id);

        return $this->sendSuccess(__('messages.flash.degree_level_update'));
    }

    /**
     * Remove the specified RequiredDegreeLevel from storage.
     *
     * @param  RequiredDegreeLevel  $requiredDegreeLevel
     * @return Response
     *
     * @throws Exception
     */
    public function destroy(RequiredDegreeLevel $requiredDegreeLevel)
    {
        $jobModels = [
            Job::class,
        ];
        $result = canDelete($jobModels, 'degree_level_id', $requiredDegreeLevel->id);
        if ($result) {
            return $this->sendError(__('messages.flash.degree_level_cant_delete'));
        }
        $requiredDegreeLevel->delete();

        return $this->sendSuccess(__('messages.flash.degree_level_delete'));
    }
}
