<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\WebHomeRepository;

class CategoriesController extends Controller {

    /** @var WebHomeRepository */
    private WebHomeRepository $homeRepository;

    public function __construct(WebHomeRepository $homeRepository) {
        $this->homeRepository = $homeRepository;
    }

    public function index() {
        $jobCategories = $this->homeRepository->getAllJobCategories();

        return view('front_web.categories.index', compact('jobCategories'));
    }

    public function fetch() {
        return $this->homeRepository->getAllJobCategories();
    }
}
