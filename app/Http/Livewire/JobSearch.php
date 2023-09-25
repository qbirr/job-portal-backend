<?php

namespace App\Http\Livewire;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use LaravelIdea\Helper\App\Models\_IH_Job_C;
use Livewire\Component;
use Livewire\WithPagination;

class JobSearch extends Component {
    use WithPagination;

    public $searchByLocation = '';

    public $types = [];

    public $category = '';

    public $salaryFrom = '';

    public $salaryTo = '';

    public $title = '';

    public $skill = '';

    public $gender = '';

    public $careerLevel = '';

    public $functionalArea = '';

    public $company = '';

    public $jobExperience = '';

    public $featuredJob = '';

    private $perPage = 10;

    protected $listeners = ['changeFilter', 'resetFilter'];

    public function paginationView(): string {
        return 'livewire.custom-pagination-company';
    }

    public function mount(Request $request): void {
        if (!empty($request->get('keywords'))) {
            $this->title = $request->get('keywords');
        }
        if (!empty($request->get('location'))) {
            $this->searchByLocation = $request->get('location');
        }
        if (!empty($request->get('categories'))) {
            $this->category = $request->get('categories');
        }
        if (!empty($request->get('company'))) {
            $this->company = $request->get('company');
        }

        $this->featuredJob = $request->is_featured;
    }

    public function nextPage($lastPage): void {
        if ($this->page < $lastPage) {
            $this->page = $this->page + 1;
            $this->setPage($this->page);
        }
    }

    public function previousPage(): void {
        if ($this->page > 1) {
            $this->page = $this->page - 1;
            $this->setPage($this->page);
        }
    }

    public function updatingSearchByLocation(): void {
        $this->resetPage();
    }

    /**
     * @param $param
     * @param $value
     */
    public function changeFilter($param, $value): void {
        $this->resetPage();
        $this->$param = $value;
    }

    public function resetFilter(): void {
        $this->reset();
    }

    /**
     * @return Factory|View
     */
    public function render(): Factory|View {
        $jobs = $this->searchJobs();

        return view('livewire.job-search', compact('jobs'));
    }

    /**
     * @param bool $withUser
     * @return array|LengthAwarePaginator|_IH_Job_C
     */
    public function searchJobs(bool $withUser = true): array|LengthAwarePaginator|_IH_Job_C {
        /** @var Job $query */
        $query = Job::with([
            'company', 'country', 'state', 'city', 'jobShift', 'jobsSkill', 'jobCategory',
        ])
            ->whereStatus(Job::STATUS_OPEN)->where('status', '!=', Job::STATUS_DRAFT)
            ->whereIsSuspended(Job::NOT_SUSPENDED)
            ->whereSubmissionStatusId(Job::SUBMISSION_STATUS_APPROVED)
            ->whereDate('job_expiry_date', '>=', Carbon::tomorrow()->toDateString());
        if ($withUser)
            $query->with(['company.user']);

        $query->when(!empty($this->types), function (Builder $q) {
            $q->whereIn('job_type_id', $this->types);
        });

        $query->when(!empty($this->category), function (Builder $q) {
            $q->where('job_category_id', '=', $this->category);
        });

        $query->when(!empty($this->salaryFrom), function (Builder $q) {
            $q->where('salary_from', '>=', $this->salaryFrom);
        });

        $query->when(!empty($this->salaryTo), function (Builder $q) {
            $q->where('salary_to', '<=', $this->salaryTo);
        });

        $query->when(!empty($this->careerLevel), function (Builder $q) {
            $q->where('career_level_id', '=', $this->careerLevel);
        });

        $query->when(!empty($this->functionalArea), function (Builder $q) {
            $q->where('functional_area_id', '=', $this->functionalArea);
        });

        $query->when($this->gender != '', function (Builder $q) {
            $q->where('no_preference', '=', $this->gender);
        });

        $query->when(!empty($this->skill), function (Builder $q) {
            $q->whereHas('jobsSkill', function (Builder $q) {
                $q->where('skill_id', '=', $this->skill);
            });
        });
        $query->when(!empty($this->company), function (Builder $q) {
            $q->whereHas('company', function (Builder $q) {
                $q->where('company_id', '=', $this->company);
            });
        });

        $query->when(!empty($this->jobExperience), function (Builder $q) {
            $q->where('experience', '=', $this->jobExperience);
        });

        $query->when(!empty($this->featuredJob), function (Builder $q) {
            $q->has('activeFeatured')
                ->whereStatus(Job::STATUS_OPEN)
                ->whereDate('job_expiry_date', '>=', Carbon::now()->toDateString())
                ->where('is_suspended', '=', Job::NOT_SUSPENDED);
        });

        $query->when(!empty($this->searchByLocation), function (Builder $q) {
            $q->where(function (Builder $q) {
                $q->where('job_title', 'like', '%' . $this->searchByLocation . '%');
                $q->orWhereHas(
                    'country',
                    function (Builder $q) {
                        $q->where('name', 'like', '%' . $this->searchByLocation . '%');
                    }
                )->orWhereHas(
                    'state',
                    function (Builder $q) {
                        $q->where('name', 'like', '%' . $this->searchByLocation . '%');
                    }
                )->orWhereHas(
                    'city',
                    function (Builder $q) {
                        $q->where('name', 'like', '%' . $this->searchByLocation . '%');
                    }
                )->orWhereHas(
                    'company.user',
                    function (Builder $q) {
                        $q->where('first_name', 'like', '%' . $this->searchByLocation . '%')
                            ->orWhere('last_name', 'like', '%' . $this->searchByLocation . '%');
                    }
                )->orWhereHas(
                    'jobsSkill',
                    function (Builder $q) {
                        $q->where('name', 'like', '%' . $this->searchByLocation . '%');
                    }
                );
            });
        });

        $query->when(!empty($this->title), function (Builder $q) {
            $q->where('job_title', 'like', '%' . $this->title . '%')
                ->orWhereHas('jobsSkill', function (Builder $q) {
                    $q->where('name', 'like', '%' . $this->title . '%');
                })
                ->orWhereHas('company.user', function (Builder $q) {
                    $q->where('first_name', 'like', '%' . $this->title . '%')
                        ->orWhere('last_name', 'like', '%' . $this->title . '%');
                });
        });

        $all = $query->paginate($this->perPage);
        $currentPage = $all->currentPage();
        $lastPage = $all->lastPage();
        if ($currentPage > $lastPage) {
            $this->page = $lastPage;
            $all = $query->paginate($this->perPage);
        }

        return $all;
    }
}
