<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Livewire\JobSearch;
use App\Http\Requests\JobSearchRequest;
use App\Repositories\JobRepository;
use App\Repositories\WebHomeRepository;
use Illuminate\Http\Request;

class JobController extends Controller {
    public function __construct(
        private readonly JobRepository $jobRepository,
        private readonly WebHomeRepository $homeRepository,
    ) {
    }

    public function latestJobs() {
        return $this->jobRepository->latestJob();
    }

    public function searchJobAutocomplete(Request $request) {
        $searchTerm = strtolower($request->get('searchTerm'));
        return $this->homeRepository->jobSearch($searchTerm);
    }

    public function searchJob(JobSearchRequest $request) {
        return $this->jobRepository->searchJob($request);
    }
}
