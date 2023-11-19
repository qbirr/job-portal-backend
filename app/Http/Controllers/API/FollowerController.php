<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\FollowerRepository;

class FollowerController extends AppBaseController {
    public function __construct(
        private readonly FollowerRepository $repository,
    ) {
    }

    public function fetchFollowers() {
        $company = auth()->user()->company;
        return $this->sendResponse($this->repository->getFollowers($company), 'Followers fetch successfully');
    }
}
