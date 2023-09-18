<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMaritalStatusRequest;
use App\Http\Requests\UpdateMaritalStatusRequest;
use App\Models\Candidate;
use App\Models\MaritalStatus;
use App\Repositories\MaritalStatusRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;

class MaritalStatusController extends AppBaseController
{
    /** @var MaritalStatusRepository */
    private $maritalStatusRepository;

    public function __construct(MaritalStatusRepository $maritalStatusRepo)
    {
        $this->maritalStatusRepository = $maritalStatusRepo;
    }

    /**
     * Display a listing of the MaritalStatus.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('marital_status.index');
    }

    /**
     * Store a newly created MaritalStatus in storage.
     *
     * @param  CreateMaritalStatusRequest  $request
     * @return JsonResponse
     */
    public function store(CreateMaritalStatusRequest $request): JsonResponse
    {
        $input = $request->all();
        $maritalStatus = $this->maritalStatusRepository->create($input);

        return $this->sendResponse($maritalStatus, __('messages.flash.marital_status_save'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return JsonResponse
     */
    public function show(MaritalStatus $maritalStatus)
    {
        return $this->sendResponse($maritalStatus, __('messages.flash.marital_status_retrieve'));
    }

    /**
     * Show the form for editing the specified MaritalStatus.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return JsonResource
     */
    public function edit(MaritalStatus $maritalStatus)
    {
        return $this->sendResponse($maritalStatus, 'Marital Status Retrieved Successfully.');
    }

    /**
     * Update the specified MaritalStatus in storage.
     *
     * @param  UpdateMaritalStatusRequest  $request
     * @param  MaritalStatus  $maritalStatus
     * @return JsonResource
     */
    public function update(UpdateMaritalStatusRequest $request, MaritalStatus $maritalStatus)
    {
        $input = $request->all();
        $this->maritalStatusRepository->update($input, $maritalStatus->id);

        return $this->sendSuccess(__('messages.flash.marital_status_update'));
    }

    /**
     * Remove the specified MaritalStatus from storage.
     *
     * @param  MaritalStatus  $maritalStatus
     * @return JsonResource
     *
     * @throws Exception
     */
    public function destroy(MaritalStatus $maritalStatus)
    {
        $candidateModels = [
            Candidate::class,
        ];
        $result = canDelete($candidateModels, 'marital_status_id', $maritalStatus->id);
        if ($result) {
            return $this->sendError('Marital Status can\'t be deleted.');
        }
        $maritalStatus->delete();

        return $this->sendSuccess(__('messages.flash.marital_status_delete'));
    }
}
