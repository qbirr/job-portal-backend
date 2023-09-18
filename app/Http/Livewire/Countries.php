<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\State;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class Countries
 */
class Countries extends Component
{
    use WithPagination;

    /**
     * @var string
     */
    public $searchCountries = '';

    /**
     * @var string[]
     */
    protected $listeners = ['refresh' => '$refresh', 'deleteCountry'];

    /**
     * @var int
     */
    private $perPage = 8;

    /**
     * @return string
     */
    public function paginationView()
    {
        return 'livewire.custom-pagenation-jobs';
    }

    /**
     * @param $lastPage
     */
    public function nextPage($lastPage)
    {
        if ($this->page < $lastPage) {
            $this->page = $this->page + 1;
        }
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page = $this->page - 1;
        }
    }

    /**
     * @param  int  $countryId
     */
    public function deleteCountry($countryId)
    {
        if (State::where('country_id', '=', $countryId)->get()->count() > 0) {
            $this->dispatchBrowserEvent('NotDeleted');
        } else {
            $country = Country::with('users')->findOrFail($countryId);
            if ($country->users()->count() > 0) {
                $this->dispatchBrowserEvent('NotDeleted');
            } else {
                $country->delete();
                $this->dispatchBrowserEvent('delete');
            }
        }
    }

    public function updatingSearchCountries()
    {
        $this->resetPage();
    }

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        $countries = $this->countries();

        return view('livewire.countries', compact('countries'));
    }

    /**
     * @return LengthAwarePaginator
     */
    public function countries()
    {
        $query = Country::query()->select('countries.*');

        $query->when(isset($this->searchCountries) && $this->searchCountries != '', function (Builder $q) {
            $q->where('name', 'like',
                '%'.strtolower($this->searchCountries).'%');
            $q->orWhere('short_code', 'like',
                '%'.strtolower($this->searchCountries).'%');
            $q->orWhere('phone_code', 'like',
                '%'.strtolower($this->searchCountries).'%');
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
