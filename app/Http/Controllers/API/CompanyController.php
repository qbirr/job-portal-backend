<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CompanySearchRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use Throwable;

class CompanyController extends AppBaseController {
    public function __construct(
        private readonly CompanyRepository $companyRepository,
    ) {
    }

    public function search(CompanySearchRequest $request) {
        return $this->companyRepository->search($request);
    }

    public function detail(Company $company) {
        if ($company->submission_status_id != Company::SUBMISSION_STATUS_APPROVED)
            return response()->json(null, 404);
        return $this->companyRepository->getCompanyDetail($company->id);
    }

    public function profile() {
        $company = auth()->user()->company;
        return $this->companyRepository->getCompanyDetail($company->id);
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateCompanyRequest $request) {
        $company = auth()->user()->company;
        $input = array_merge($request->all(), ['region_code' => 974]);
        $this->companyRepository->update($input, $company);
        return $this->sendSuccess('Company updated');
    }

    public function reportToCompany(Request $request, Company $company) {
        $input = array_merge($request->all(),
            [
                'companyId' => $company->id,
                'userId' => auth()->id(),
            ]
        );
        $this->companyRepository->storeReportToCompany($input);

        return $this->sendSuccess(__('messages.flash.report_to_company'));
    }
}
