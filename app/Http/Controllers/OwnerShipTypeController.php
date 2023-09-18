<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOwnerShipTypeRequest;
use App\Http\Requests\UpdateOwnerShipTypeRequest;
use App\Models\Company;
use App\Models\OwnerShipType;
use App\Repositories\OwnerShipTypeRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OwnerShipTypeController extends AppBaseController
{
    /** @var OwnerShipTypeRepository */
    private $ownerShipTypeRepository;

    public function __construct(OwnerShipTypeRepository $ownerShipTypeRepo)
    {
        $this->ownerShipTypeRepository = $ownerShipTypeRepo;
    }

    /**
     * Display a listing of the OwnerShipType.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('ownership_types.index');
    }

    /**
     * Store a newly created OwnerShipType in storage.
     *
     * @param  CreateOwnerShipTypeRequest  $request
     * @return JsonResponse
     */
    public function store(CreateOwnerShipTypeRequest $request): JsonResponse
    {
        $input = $request->all();

        $ownerShipType = $this->ownerShipTypeRepository->create($input);

        return $this->sendResponse($ownerShipType, __('messages.flash.ownership_type_save'));
    }

    /**
     * Display the specified OwnerShipType.
     *
     * @param  OwnerShipType  $ownerShipType
     * @return Response
     */
    public function show(OwnerShipType $ownerShipType)
    {
        return $this->sendResponse($ownerShipType, __('messages.flash.ownership_type_retrieve'));
    }

    /**
     * Show the form for editing the specified OwnerShipType.
     *
     * @param  OwnerShipType  $ownerShipType
     * @return Response
     */
    public function edit(OwnerShipType $ownerShipType)
    {
        return $this->sendResponse($ownerShipType, 'OwnerShip Type retrieved successfully.');
    }

    /**
     * Update the specified OwnerShipType in storage.
     *
     * @param  OwnerShipType  $ownerShipType
     * @param  UpdateOwnerShipTypeRequest  $request
     * @return Response
     */
    public function update(OwnerShipType $ownerShipType, UpdateOwnerShipTypeRequest $request)
    {
        $ownerShipType = $this->ownerShipTypeRepository->update($request->all(), $ownerShipType->id);

        return $this->sendSuccess(__('messages.flash.ownership_type_updated'));
    }

    /**
     * Remove the specified OwnerShipType from storage.
     *
     * @param  OwnerShipType  $ownerShipType
     * @return Response
     *
     * @throws Exception
     */
    public function destroy(OwnerShipType $ownerShipType)
    {
        $companyModels = [
            Company::class,
        ];
        $result = canDelete($companyModels, 'ownership_type_id', $ownerShipType->id);
        if ($result) {
            return $this->sendError(__('messages.flash.ownership_type_cant_delete'));
        }
        $this->ownerShipTypeRepository->delete($ownerShipType->id);

        return $this->sendSuccess(__('messages.flash.ownership_type_delete'));
    }
}
