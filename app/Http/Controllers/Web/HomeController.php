<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\ContactFormRequest;
use App\Models\CmsServices;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\JobRepository;
use App\Repositories\WebHomeRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Laracasts\Flash\Flash;

class HomeController extends AppBaseController {

    public function __construct(
        private readonly WebHomeRepository $homeRepository,
        private readonly JobRepository $jobRepository,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index() {
        $data = $this->getData();
        $data['color'] = Setting::COLOR;
        return view('front_web.home.home')->with($data);
    }

    /**
     * @param ContactFormRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function sendContactEmail(ContactFormRequest $request) {
        $inquiry = $this->homeRepository->storeInquires($request->all());
        Flash::success('Thank you for contacting us.');

        return redirect(route('front.contact'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeLanguage(Request $request) {
        $language = $request->input('languageName');

        Session::put('languageName', $language);

        /** @var User $user */
        $user = getLoggedInUser();
        $user->update(['language' => $language]);

        return $this->sendSuccess(__('messages.flash.language_changed'));
    }

    /**
     * @param Request $request
     * @return array|string
     *
     * @throws Throwable
     */
    public function getJobsSearch(Request $request) {
        $searchTerm = strtolower($request->get('searchTerm'));

        $results = $this->homeRepository->jobSearch($searchTerm);

        return view('front_web.home.job_search_results', compact('results'))->render();
    }

    /**
     * @return array
     */
    public function getData(): array {
        $data['testimonials'] = $this->homeRepository->getTestimonials();
        $data['dataCounts'] = $this->homeRepository->getDataCounts();
//        $data['latestJobs'] = $this->homeRepository->getLatestJobs()->take(4);
        $data['latestJobs'] = $this->jobRepository->latestJob()->take(4);
        $data['categories'] = $this->homeRepository->getCategories();
        $data['jobCategories'] = $this->homeRepository->getAllJobCategories()->where('is_featured', 1)->take(8);
        $data['featuredCompanies'] = $this->homeRepository->getFeaturedCompanies();
        $data['allCompanies'] = $this->homeRepository->getAllCompanies(submission_status: 2);
        $data['featuredJobs'] = $this->homeRepository->getFeaturedJobs();
        $data['notices'] = $this->homeRepository->getNotices();
        [$data['imageSliders'], $data['settings'], $data['slider'], $data['imageSliderActive'], $data['headerSliders']] = $this->homeRepository->getImageSlider();
        $data['latestJobsEnable'] = $this->homeRepository->getLatestJobsEnable();
        $data['plans'] = $this->homeRepository->getPlans();
        $data['plansEnable'] = getSettingValue('enable_subscription_plan') && count($data['plans']) > 0;
        $data['plansArray'] = array_chunk($data['plans']->toArray(), 3);
        $data['branding'] = $this->homeRepository->getBranding();
        $data['recentBlog'] = $this->homeRepository->getRecentBlog();
        $data['cmsServices'] = CmsServices::pluck('value', 'key')->toArray();
        return $data;
    }

    public function frontJson() {
        return response()->json($this->getData(), 200, [], JSON_NUMERIC_CHECK);
    }
}
