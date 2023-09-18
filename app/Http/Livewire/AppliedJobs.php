<?php

namespace App\Http\Livewire;

use App\Models\JobApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AppliedJobs extends Component
{
    use WithPagination;

    /**
     * @var string
     */
    public $searchByAppliedJob = '';

    public $jobApplicationStatus = '';

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['removeAppliedJob', 'changeFilter', 'resetFilter'];

    /**
     * @var int
     */
    private $perPage = 6;

    /**
     * @return string
     */
    public function paginationView()
    {
        return 'livewire.custom-pagenation-jobs';
    }

    public function nextPage($lastPage)
    {
        if ($this->page < $lastPage) {
            $this->page = $this->page + 1;
            $this->setPage($this->page);
        }
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page = $this->page - 1;
            $this->setPage($this->page);
        }
    }

    /**
     * @param $param
     * @param $value
     */
    public function changeFilter($param, $value)
    {
        $this->resetPage();
        $this->$param = $value;
    }

    public function resetFilter()
    {
        $this->reset();
    }

    /**
     * @param $id
     */
    public function removeAppliedJob($id)
    {
        $appliedJob = JobApplication::with('applicationSchedule')->findOrFail($id);

        $candidateId = getLoggedInUser()->candidate->id;
        $jobApplicationIds = JobApplication::whereCandidateId($candidateId)->pluck('id')->toArray();

        if (! in_array($id, $jobApplicationIds)) {
            $this->dispatchBrowserEvent('appliedJob:error');

            if ($appliedJob->applicationSchedule->count() > 0) {
                $this->dispatchBrowserEvent('notDeleted');
            }
        } else {
            $appliedJob->delete();
            $this->dispatchBrowserEvent('deleted');
        }

//        $appliedJob = JobApplication::with('applicationSchedule')->findOrFail($id);
//        if ($appliedJob->applicationSchedule->count() > 0) {
//            $this->dispatchBrowserEvent('notDeleted');
//        } else {
//            $appliedJob->delete($id);
//            $this->dispatchBrowserEvent('deleted');
//        }
    }

    public function updatingsearchByAppliedJob()
    {
        $this->resetPage();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        $appliedJobs = $this->searchAppliedJob();
        $jobApplicationStatusArr = JobApplication::STATUS;

        return view('livewire.applied-jobs', compact('appliedJobs', 'jobApplicationStatusArr'));
    }

    /**
     * @return LengthAwarePaginator
     */
    public function searchAppliedJob()
    {
        $query = JobApplication::with(['candidate.user', 'job.currency', 'jobStage'])->where('candidate_id',
            getLoggedInUser()->owner_id)->orderByDesc('created_at');

        if ($this->jobApplicationStatus != '') {
            $query->where('status', $this->jobApplicationStatus);
        }

        $query->when($this->searchByAppliedJob != '', function (Builder $query) {
            $query->where(function (Builder $query) {
                $query->whereHas('job', function (Builder $query) {
                    $query->where('job_title', 'like', '%'.strtolower($this->searchByAppliedJob).'%');
                });

                $query->orWhere('created_at', 'like', '%'.strtolower($this->searchByAppliedJob).'%');
                $query->orWhere('expected_salary', 'like', '%'.strtolower($this->searchByAppliedJob).'%');
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
