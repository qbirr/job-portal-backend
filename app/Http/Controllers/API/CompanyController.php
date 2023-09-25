<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanySearchRequest;
use App\Repositories\CompanyRepository;

class CompanyController extends Controller {
    public function __construct(
        private readonly CompanyRepository $companyRepository,
    ) {
    }

    public function search(CompanySearchRequest $request) {
        return $this->companyRepository->search($request);
    }
}
