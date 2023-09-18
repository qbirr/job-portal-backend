<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanySizeRequest;
use App\Http\Requests\UpdateCompanySizeRequest;
use App\Models\Company;
use App\Models\CompanySize;
use App\Repositories\CompanySizeRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanySizeController extends AppBaseController
{
    /** @var CompanySizeRepository */
    private $companySizeRepository;

    public function __construct(CompanySizeRepository $companySizeRepo)
    {
        $this->companySizeRepository = $companySizeRepo;
    }

    /**
     * Display a listing of the CompanySize.
     *
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index()
    {
        return view('company_sizes.index');
    }

    /**
     * Store a newly created CompanySize in storage.
     *
     * @param  CreateCompanySizeRequest  $request
     * @return JsonResponse
     */
    public function store(CreateCompanySizeRequest $request): JsonResponse
    {
        $input = $request->all();
        $companySize = $this->companySizeRepository->create($input);

        return $this->sendResponse($companySize, __('messages.flash.company_size_save'));
    }

    /**
     * Show the form for editing the specified CompanySize.
     *
     * @param  CompanySize  $companySize
     * @return JsonResponse
     */
    public function edit(CompanySize $companySize)
    {
        return $this->sendResponse($companySize, __('messages.flash.retrieved'));
    }

    /**
     * Update the specified CompanySize in storage.
     *
     * @param  UpdateCompanySizeRequest  $request
     * @param  CompanySize  $companySize
     * @return JsonResponse
     */
    public function update(UpdateCompanySizeRequest $request, CompanySize $companySize)
    {
        $input = $request->all();
        $this->companySizeRepository->update($input, $companySize->id);

        return $this->sendSuccess(__('messages.flash.company_size_update'));
    }

    /**
     * Remove the specified CompanySize from storage.
     *
     * @param  CompanySize  $companySize
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(CompanySize $companySize)
    {
        $companyModels = [
            Company::class,
        ];
        $result = canDelete($companyModels, 'company_size_id', $companySize->id);
        if ($result) {
            return $this->sendError(__('messages.flash.company_size_cant_delete'));
        }
        $companySize->delete();

        return $this->sendSuccess(__('messages.flash.company_size_delete'));
    }
}
