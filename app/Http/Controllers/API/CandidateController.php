<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CandidateSearchRequest;
use App\Models\Candidate;
use App\Repositories\Candidates\CandidateRepository;

class CandidateController extends AppBaseController {
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

    public function profile() {
        $user = auth()->user();
        $data['candidate'] = Candidate::whereUserId($user->id)->first();
        return $this->sendResponse($data, 'Candidate retrieved successfully.');
    }
}
