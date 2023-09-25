<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateSearchRequest;
use App\Models\Candidate;
use App\Repositories\Candidates\CandidateRepository;

class CandidateController extends Controller {
    public function __construct(
        private readonly CandidateRepository $candidateRepository,
    ) {
    }

    public function search(CandidateSearchRequest $request) {
        return $this->candidateRepository->search($request);
    }

    public function detail(Candidate $candidate) {
        return $this->candidateRepository->getCandidateDetail($candidate->id);
    }
}
