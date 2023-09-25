<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanySearchRequest;
use App\Models\Company;
use App\Repositories\CompanyRepository;

class CompanyController extends Controller {
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
}
