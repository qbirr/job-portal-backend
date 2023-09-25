<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\WebHomeRepository;
use Illuminate\Http\Request;

class JobController extends Controller {
    public function __construct(
        private readonly WebHomeRepository $homeRepository,
    ) {
    }

    public function getJobsSearch(Request $request) {
        $searchTerm = strtolower($request->get('searchTerm'));

        $results = $this->homeRepository->jobSearch($searchTerm);
        return $results;
    }
}
