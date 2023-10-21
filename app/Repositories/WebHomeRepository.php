<?php

namespace App\Repositories;

use App\Models\BrandingSliders;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\EmailTemplate;
use App\Models\FrontSetting;
use App\Models\HeaderSlider;
use App\Models\ImageSlider;
use App\Models\Inquiry;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Noticeboard;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use LaravelIdea\Helper\App\Models\_IH_Company_C;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class WebHomeRepository
 *
 * @version July 7, 2020, 5:07 am UTC
 */
class WebHomeRepository {
    /**
     * @return mixed
     */
    public function getTestimonials(): mixed {
        return Testimonial::with('media')->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * @return array
     */
    public function getDataCounts(): array {
        $data['candidates'] = Candidate::with('user')->whereHas(
            'user',
            function (Builder $query) {
                $query->where('is_active', '=', true);
            }
        )->count();
        $data['jobs'] = Job::where('job_expiry_date', '>=', date('Y-m-d'))->status(Job::STATUS_OPEN)->count();
        $data['resumes'] = Media::where('model_type', Candidate::class)->where(
            'collection_name',
            Candidate::RESUME_PATH
        )->count();
        $data['companies'] = Company::with('user')->whereHas('user', function (Builder $query) {
            $query->where('is_active', '=', true);
        })->count();

        return $data;
    }

    public function getLatestJobs(): Collection|array {
        return Job::with(['company', 'company.user:id,first_name,last_name', 'jobCategory', 'jobsSkill', 'functionalArea'])
            ->whereStatus(Job::STATUS_OPEN)
            ->whereIsSuspended(Job::NOT_SUSPENDED)
            ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->append('full_location');
    }

    /**
     * @return JobCategory[]|Builder[]|Collection
     */
    public function getCategories(): Collection|array {
        return JobCategory::whereIsFeatured(1)
            ->withCount([
                'jobs' => function (Builder $q) {
                    $q->where('status', '!=', Job::STATUS_DRAFT);
                    $q->where('status', '!=', Job::STATUS_CLOSED);
                },
            ])
            ->orderBy('jobs_count', 'desc')
            ->toBase()
            ->take(8)
            ->get();
    }

    /**
     * @return Collection
     */
    public function getAllJobCategories(): Collection {
        return JobCategory::with('media')->withCount([
            'jobs' => function (Builder $q) {
                $q->whereStatus(Job::STATUS_OPEN)
                    ->where('status', '!=', Job::STATUS_DRAFT)
                    ->whereIsSuspended(Job::NOT_SUSPENDED)
                    ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString());
            },
        ])->get();
    }

    /**
     * @return Company[]|Builder[]|Collection
     */
    public function getFeaturedCompanies(): Collection|array {
        return Company::has('activeFeatured')
            ->with(['jobs', 'activeFeatured', 'user' => function ($query) {
                $query->without(['country', 'state', 'city']);
            }])
            ->whereHas('user', function (Builder $query) {
                $query->where('is_active', '=', true);
            })
            ->withCount(['jobs' => function (Builder $q) {
                $q->where('status', '!=', Job::STATUS_DRAFT);
                $q->where('status', '!=', Job::STATUS_CLOSED);
            }])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAllCompanies(): Collection|array|_IH_Company_C {
        return Company::with('activeFeatured', 'jobs')->withCount(['jobs' => function (Builder $q) {
            $q->where('status', '!=', Job::STATUS_DRAFT);
            $q->where('status', '!=', Job::STATUS_CLOSED);
        }])->get();
    }

    /**
     * @return Job[]|Builder[]|Collection
     */
    public function getFeaturedJobs(): Collection|array {
        return Job::has('activeFeatured')
            ->whereStatus(Job::STATUS_OPEN)
            ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
            ->where('is_suspended', '=', Job::NOT_SUSPENDED)
            ->with(['company', 'jobCategory', 'jobsSkill'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return Noticeboard[]|Builder[]|Collection
     */
    public function getNotices(): Collection|array {
        return Noticeboard::whereIsActive(true)->orderByDesc('created_at')->get();
    }

    /**
     * @param $input
     * @return bool
     */
    public function storeInquires($input): bool {
        /** @var Inquiry $inquiry */
        $inquiry = Inquiry::create($input);
        $templateBody = EmailTemplate::whereTemplateName('Contact Us')->first();
        $keyVariable = ['{{name}}', '{{phone_no}}', '{{from_name}}'];
        $value = [$inquiry->name, $inquiry->phone_no, config('app.name')];
        $body = str_replace($keyVariable, $value, $templateBody->body);
        $data['inquiry'] = $inquiry;
        $data['body'] = $body;
//        Mail::to($input['email'])->send(new ContactEmail($data));

        return true;
    }

    /**
     * @return array
     */
    public function getImageSlider(): array {
        $imageSliders = ImageSlider::with('media')
            ->where('is_active', '=', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        $headerSliders = HeaderSlider::with('media')
            ->where('is_active', '=', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        $settings = Setting::where('key', 'slider_is_active')->toBase()->first();
        $slider = Setting::where('key', 'is_full_slider')->toBase()->first();
        $imageSliderActive = Setting::where('key', 'is_slider_active')->toBase()->first();

        return [$imageSliders, $settings, $slider, $imageSliderActive, $headerSliders];
    }

    /**
     * @return FrontSetting
     */
    public function getLatestJobsEnable(): FrontSetting {
        return FrontSetting::where('key', 'latest_jobs_enable')->first();
    }

    /**
     * @return mixed
     */
    public function getPlans(): mixed {
        return $plans = Plan::with('salaryCurrency')->get()->sortBy('amount', SORT_DESC, true);
    }

    /**
     * @return BrandingSliders[]|Collection
     */
    public function getBranding(): Collection|array {
        return $branding = BrandingSliders::with('media')
            ->where('is_active', '=', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return Builder[]|Collection
     */
    public function getRecentBlog(): Collection|array {
        return Post::with(
            [
                'postAssignCategories',
                'media',
                'user' => function ($query) {
                    $query->without(['media', 'country', 'state', 'city']);
                },
            ]
        )->withCount('comments')
            ->orderBy('created_at', 'desc')->limit(3)
            ->get();
    }

    /**
     * @param $searchTerm
     * @return array
     */
    public function jobSearch($searchTerm): ?array {
        if ($searchTerm) {
            $jobSearchResult = Job::whereSubmissionStatusId(2)
                ->where('job_title', 'LIKE', '%' . $searchTerm . '%')->get();
            $skills = Skill::where('name', 'LIKE', '%' . $searchTerm . '%')->get();
            $companies = Company::whereHas(
                'user',
                function (Builder $query) use ($searchTerm) {
                    $query->where('first_name', 'LIKE', '%' . $searchTerm . '%')->orWhere(
                        'last_name',
                        'LIKE',
                        '%' . $searchTerm . '%'
                    );
                }
            )->get();

            $jobTitle = [];
            $skillName = [];
            $companyName = [];
            if (!$jobSearchResult->isEmpty() || !$skills->isEmpty() || !$companies->isEmpty()) {
                foreach ($jobSearchResult as $jobSearch) {
                    $jobTitle[] = $jobSearch->job_title;
                }
                foreach ($skills as $skill) {
                    $skillName[] = $skill->name;
                }
                foreach ($companies as $company) {
                    $companyName[] = $company->user->full_name;
                }
            }
            $allResult = array_merge($jobTitle, $skillName, $companyName);
            return array_unique($allResult);
        }
        return null;
    }
}
